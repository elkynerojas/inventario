<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use App\Models\AsignacionActivo;
use App\Models\ImportacionActivo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ImportacionActivoController extends Controller
{
    /**
     * Mostrar formulario de importación
     */
    public function index(): View
    {
        $importaciones = ImportacionActivo::with('usuario')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('importaciones.index', compact('importaciones'));
    }

    /**
     * Mostrar formulario para subir archivo
     */
    public function create(): View
    {
        return view('importaciones.create');
    }

    /**
     * Procesar archivo Excel subido
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB máximo
        ]);

        try {
            $archivo = $request->file('archivo');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            
            // Asegurar que el directorio existe
            $directorio = storage_path('app/importaciones');
            if (!is_dir($directorio)) {
                mkdir($directorio, 0755, true);
            }
            
            // Guardar archivo
            $rutaCompleta = $directorio . '/' . $nombreArchivo;
            $archivo->move($directorio, $nombreArchivo);
            
            // Verificar que el archivo se guardó correctamente
            if (!file_exists($rutaCompleta)) {
                throw new \Exception('Error al guardar el archivo en el servidor');
            }
            
            $rutaArchivo = 'importaciones/' . $nombreArchivo;

            // Crear registro de importación
            $importacion = ImportacionActivo::create([
                'nombre_archivo' => $archivo->getClientOriginalName(),
                'ruta_archivo' => $rutaArchivo,
                'estado' => 'procesando',
                'user_id' => auth()->id(),
            ]);

            // Procesar archivo de forma síncrona
            $this->procesarArchivo($importacion);

            return redirect()->route('importaciones.show', $importacion)
                ->with('success', 'Archivo procesado correctamente. La importación se completó.');

        } catch (\Exception $e) {
            Log::error('Error al procesar archivo de importación: ' . $e->getMessage());
            
            // Si hay una importación creada, marcarla como fallida
            if (isset($importacion)) {
                $importacion->update([
                    'estado' => 'fallido',
                    'errores' => [['error' => $e->getMessage()]],
                ]);
            }
            
            return redirect()->back()
                ->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de una importación
     */
    public function show(ImportacionActivo $importacion): View
    {
        $importacion->load('usuario');
        return view('importaciones.show', compact('importacion'));
    }

    /**
     * Obtener estado de importación via AJAX
     */
    public function estado(ImportacionActivo $importacion): JsonResponse
    {
        // Recargar el modelo para obtener datos actualizados
        $importacion->refresh();
        
        return response()->json([
            'estado' => $importacion->estado,
            'estadisticas' => $importacion->estadisticas,
            'errores' => $importacion->errores,
            'total_registros' => $importacion->total_registros,
            'registros_procesados' => $importacion->registros_procesados,
            'registros_exitosos' => $importacion->registros_exitosos,
            'registros_fallidos' => $importacion->registros_fallidos,
        ]);
    }

    /**
     * Descargar plantilla de ejemplo
     */
    public function plantilla(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $rutaPlantilla = storage_path('app/plantillas/plantilla_activos.xlsx');
        
        if (!file_exists($rutaPlantilla)) {
            $this->crearPlantilla();
        }

        return response()->download($rutaPlantilla, 'plantilla_activos.xlsx');
    }

    /**
     * Procesar archivo Excel
     */
    private function procesarArchivo(ImportacionActivo $importacion): void
    {
        $rutaCompleta = storage_path('app/' . $importacion->ruta_archivo);
        
        // Verificar que el archivo existe
        if (!file_exists($rutaCompleta)) {
            throw new \Exception("El archivo no existe en la ruta: {$rutaCompleta}");
        }
        
        // Leer archivo Excel
        $datos = Excel::toArray(new class implements ToModel, WithHeadingRow {
            public function model(array $row)
            {
                return $row;
            }
        }, $rutaCompleta);

        if (empty($datos) || empty($datos[0])) {
            throw new \Exception('El archivo está vacío o no tiene datos válidos.');
        }

        $filas = $datos[0];
        $totalRegistros = count($filas);
        $importacion->update(['total_registros' => $totalRegistros]);

        $registrosExitosos = 0;
        $registrosFallidos = 0;
        $errores = [];

        DB::beginTransaction();

        try {
            foreach ($filas as $indice => $fila) {
                try {
                    $this->procesarFila($fila, $indice + 2); // +2 porque empieza en fila 2 (después del encabezado)
                    $registrosExitosos++;
                } catch (\Exception $e) {
                    $registrosFallidos++;
                    $errores[] = [
                        'fila' => $indice + 2,
                        'error' => $e->getMessage(),
                        'datos' => $fila
                    ];
                    // Log del error para debugging
                    Log::error("Error procesando fila " . ($indice + 2) . ": " . $e->getMessage());
                }

                $importacion->update([
                    'registros_procesados' => $indice + 1,
                    'registros_exitosos' => $registrosExitosos,
                    'registros_fallidos' => $registrosFallidos,
                ]);
            }

            DB::commit();

            $importacion->update([
                'estado' => 'completado',
                'errores' => $errores,
                'observaciones' => [
                    'fecha_procesamiento' => now()->format('Y-m-d H:i:s'),
                    'total_errores' => count($errores),
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e; // Re-lanzar la excepción para que sea manejada en el método store
        }
    }

    /**
     * Procesar una fila individual
     */
    private function procesarFila(array $fila, int $numeroFila): void
    {
        // Validar campos requeridos
        $camposRequeridos = ['codigo', 'nombre', 'estado'];
        foreach ($camposRequeridos as $campo) {
            if (empty($fila[$campo])) {
                throw new \Exception("Campo requerido '{$campo}' está vacío en la fila {$numeroFila}");
            }
        }

        // Buscar activo existente por código
        $activo = Activo::where('codigo', $fila['codigo'])->first();

        if ($activo) {
            // Actualizar activo existente
            $this->actualizarActivo($activo, $fila, $numeroFila);
        } else {
            // Crear nuevo activo
            $activo = $this->crearActivo($fila, $numeroFila);
        }

        // Manejar asignación automática si se proporciona codigo_responsable
        if (!empty($fila['codigo_responsable'])) {
            $this->manejarAsignacionAutomatica($activo, $fila['codigo_responsable'], $numeroFila);
        }
    }

    /**
     * Crear nuevo activo
     */
    private function crearActivo(array $fila, int $numeroFila): Activo
    {
        return Activo::create([
            'codigo' => $fila['codigo'],
            'nombre' => $fila['nombre'],
            'estado' => $fila['estado'],
            'valor_compra' => $fila['valor_compra'] ?? null,
            'fecha_compra' => $fila['fecha_compra'] ?? null,
            'marca' => $fila['marca'] ?? null,
            'modelo' => $fila['modelo'] ?? null,
            'serial' => $fila['serial'] ?? null,
            'ubicacion' => $fila['ubicacion'] ?? null,
            'nombre_responsable' => $fila['nombre_responsable'] ?? null,
            'codigo_responsable' => $fila['codigo_responsable'] ?? null,
            'tipo_bien' => $fila['tipo_bien'] ?? null,
            'observacion' => $fila['observacion'] ?? null,
            'descripcion' => $fila['descripcion'] ?? null,
            'codigo_grupo_articulo' => $fila['codigo_grupo_articulo'] ?? null,
            'codigo_grupo_contable' => $fila['codigo_grupo_contable'] ?? null,
            'codigo_servicio' => $fila['codigo_servicio'] ?? null,
            'nro_compra' => $fila['nro_compra'] ?? null,
            'vida_util' => $fila['vida_util'] ?? null,
            'fecha_depreciacion' => $fila['fecha_depreciacion'] ?? null,
            'valor_depreciacion' => $fila['valor_depreciacion'] ?? null,
            'recurso' => $fila['recurso'] ?? null,
            'tipo_adquisicion' => $fila['tipo_adquisicion'] ?? null,
            'tipo_hoja_vi' => $fila['tipo_hoja_vi'] ?? null,
            'area_administrativa' => $fila['area_administrativa'] ?? null,
            'valor_residual' => $fila['valor_residual'] ?? null,
        ]);
    }

    /**
     * Actualizar activo existente
     */
    private function actualizarActivo(Activo $activo, array $fila, int $numeroFila): void
    {
        $activo->update([
            'nombre' => $fila['nombre'],
            'estado' => $fila['estado'],
            'valor_compra' => $fila['valor_compra'] ?? $activo->valor_compra,
            'fecha_compra' => $fila['fecha_compra'] ?? $activo->fecha_compra,
            'marca' => $fila['marca'] ?? $activo->marca,
            'modelo' => $fila['modelo'] ?? $activo->modelo,
            'serial' => $fila['serial'] ?? $activo->serial,
            'ubicacion' => $fila['ubicacion'] ?? $activo->ubicacion,
            'nombre_responsable' => $fila['nombre_responsable'] ?? $activo->nombre_responsable,
            'codigo_responsable' => $fila['codigo_responsable'] ?? $activo->codigo_responsable,
            'tipo_bien' => $fila['tipo_bien'] ?? $activo->tipo_bien,
            'observacion' => $fila['observacion'] ?? $activo->observacion,
            'descripcion' => $fila['descripcion'] ?? $activo->descripcion,
            'codigo_grupo_articulo' => $fila['codigo_grupo_articulo'] ?? $activo->codigo_grupo_articulo,
            'codigo_grupo_contable' => $fila['codigo_grupo_contable'] ?? $activo->codigo_grupo_contable,
            'codigo_servicio' => $fila['codigo_servicio'] ?? $activo->codigo_servicio,
            'nro_compra' => $fila['nro_compra'] ?? $activo->nro_compra,
            'vida_util' => $fila['vida_util'] ?? $activo->vida_util,
            'fecha_depreciacion' => $fila['fecha_depreciacion'] ?? $activo->fecha_depreciacion,
            'valor_depreciacion' => $fila['valor_depreciacion'] ?? $activo->valor_depreciacion,
            'recurso' => $fila['recurso'] ?? $activo->recurso,
            'tipo_adquisicion' => $fila['tipo_adquisicion'] ?? $activo->tipo_adquisicion,
            'tipo_hoja_vi' => $fila['tipo_hoja_vi'] ?? $activo->tipo_hoja_vi,
            'area_administrativa' => $fila['area_administrativa'] ?? $activo->area_administrativa,
            'valor_residual' => $fila['valor_residual'] ?? $activo->valor_residual,
        ]);
    }

    /**
     * Manejar asignación automática del activo al usuario
     */
    private function manejarAsignacionAutomatica(Activo $activo, string $codigoResponsable, int $numeroFila): void
    {
        // Buscar usuario por número de documento
        $usuario = User::where('documento', $codigoResponsable)->first();
        
        if (!$usuario) {
            // Log del error pero no interrumpir el procesamiento
            Log::warning("No se encontró usuario con documento '{$codigoResponsable}' en la fila {$numeroFila} para el activo {$activo->codigo}");
            return; // Continuar sin asignar
        }

        // Obtener asignación activa actual
        $asignacionActual = $activo->asignacionActiva;

        if ($asignacionActual) {
            // Verificar si la asignación actual es al mismo usuario
            if ($asignacionActual->user_id === $usuario->id) {
                // Ya está asignado al usuario correcto, no hacer nada
                return;
            } else {
                // Cambiar asignación anterior a estado "devuelta"
                $asignacionActual->update([
                    'estado' => 'devuelta',
                    'fecha_devolucion' => now()->toDateString(),
                    'observaciones' => $asignacionActual->observaciones . ' | Devuelto automáticamente durante importación - nuevo responsable: ' . $usuario->name,
                ]);
            }
        }

        // Crear nueva asignación automática
        AsignacionActivo::create([
            'activo_id' => $activo->id,
            'user_id' => $usuario->id,
            'asignado_por' => auth()->id(), // Usuario que realiza la importación
            'fecha_asignacion' => now()->toDateString(),
            'estado' => 'activa',
            'observaciones' => 'Asignación automática durante importación desde Excel',
            'ubicacion_asignada' => $activo->ubicacion,
        ]);
    }

    /**
     * Crear plantilla de ejemplo
     */
    private function crearPlantilla(): void
    {
        $directorio = storage_path('app/plantillas');
        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        $plantilla = [
            ['codigo', 'nombre', 'estado', 'valor_compra', 'fecha_compra', 'marca', 'modelo', 'serial', 'ubicacion', 'nombre_responsable', 'codigo_responsable', 'tipo_bien', 'observacion', 'descripcion'],
            ['ACT001', 'Laptop Dell', 'bueno', '1500.00', '2024-01-15', 'Dell', 'Inspiron 15', 'DL123456', 'Oficina Principal', 'MARTHA PARADA', '60258814', 'Equipo', 'Laptop para desarrollo', 'Laptop Dell Inspiron 15 pulgadas'],
            ['ACT002', 'Monitor Samsung', 'bueno', '300.00', '2024-01-20', 'Samsung', '24 pulgadas', 'SM789012', 'Oficina Principal', 'Ana Isabel Camacho', '60261692', 'Equipo', 'Monitor para oficina', 'Monitor Samsung 24 pulgadas Full HD'],
            ['ACT003', 'Impresora HP', 'bueno', '200.00', '2024-01-25', 'HP', 'LaserJet Pro', 'HP987654', 'Oficina Secundaria', 'Anais Quintana Diaz', '60250586', 'Equipo', 'Impresora para oficina', 'Impresora HP LaserJet Pro'],
            ['ACT004', 'Teclado Logitech', 'bueno', '50.00', '2024-01-30', 'Logitech', 'K120', 'LG456789', 'Oficina Principal', 'CAICEDO CARRILLO BLANCA ESTELLA', '60254588', 'Equipo', 'Teclado para oficina', 'Teclado Logitech K120 USB'],
            ['ACT005', 'Mouse Microsoft', 'bueno', '30.00', '2024-02-01', 'Microsoft', 'Basic Optical', 'MS789123', 'Oficina Principal', 'clara ines', '60258755', 'Equipo', 'Mouse para oficina', 'Mouse Microsoft Basic Optical'],
        ];

        // Crear el archivo Excel usando una clase simple
        $export = new class($plantilla) implements FromArray, WithHeadings {
            private $datos;
            
            public function __construct($datos) {
                $this->datos = $datos;
            }
            
            public function array(): array
            {
                return array_slice($this->datos, 1); // Excluir el encabezado
            }
            
            public function headings(): array
            {
                return $this->datos[0]; // Solo el encabezado
            }
        };

        try {
            Excel::store($export, 'plantillas/plantilla_activos.xlsx', 'local');
        } catch (\Exception $e) {
            Log::error('Error al crear plantilla: ' . $e->getMessage());
            throw $e;
        }
    }
}

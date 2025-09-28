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
            'codigo_grupo_articulo' => $fila['codigo_grupo_articulo'] ?? null,
            'codigo_grupo_contable' => $fila['codigo_grupo_contable'] ?? null,
            'tipo_bien' => $fila['tipo_bien'] ?? null,
            'codigo_servicio' => $fila['codigo_servicio'] ?? null,
            'codigo_responsable' => $fila['codigo_responsable'] ?? null,
            'nombre_responsable' => $fila['nombre_responsable'] ?? null,
            'fecha_compra' => $fila['fecha_compra'] ?? null,
            'nro_compra' => $fila['nro_compra'] ?? null,
            'vida_util' => $fila['vida_util'] ?? null,
            'estado' => $fila['estado'],
            'modelo' => $fila['modelo'] ?? null,
            'marca' => $fila['marca'] ?? null,
            'serial' => $fila['serial'] ?? null,
            'fecha_depreciacion' => $fila['fecha_depreciacion'] ?? null,
            'valor_compra' => $fila['valor_compra'] ?? null,
            'valor_depreciacion' => $fila['valor_depreciacion'] ?? null,
            'ubicacion' => $fila['ubicacion'] ?? null,
            'recurso' => $fila['recurso'] ?? null,
            'tipo_adquisicion' => $fila['tipo_adquisicion'] ?? null,
            'observacion' => $fila['observacion'] ?? null,
            'descripcion' => $fila['descripcion'] ?? null,
            'tipo_hoja_vi' => $fila['tipo_hoja_vi'] ?? null,
            'area_administrativa' => $fila['area_administrativa'] ?? null,
            'valor_residual' => $fila['valor_residual'] ?? null,
            'fecha_creacion' => $fila['fecha_creacion'] ?? null,
            'grupo_articulo' => $fila['grupo_articulo'] ?? null,
            'fecha_depre' => $fila['fecha_depre'] ?? null,
            't_adquisicion' => $fila['t_adquisicion'] ?? null,
            'tipo_hoja' => $fila['tipo_hoja'] ?? null,
        ]);
    }

    /**
     * Actualizar activo existente
     */
    private function actualizarActivo(Activo $activo, array $fila, int $numeroFila): void
    {
        $activo->update([
            'nombre' => $fila['nombre'],
            'codigo_grupo_articulo' => $fila['codigo_grupo_articulo'] ?? $activo->codigo_grupo_articulo,
            'codigo_grupo_contable' => $fila['codigo_grupo_contable'] ?? $activo->codigo_grupo_contable,
            'tipo_bien' => $fila['tipo_bien'] ?? $activo->tipo_bien,
            'codigo_servicio' => $fila['codigo_servicio'] ?? $activo->codigo_servicio,
            'codigo_responsable' => $fila['codigo_responsable'] ?? $activo->codigo_responsable,
            'nombre_responsable' => $fila['nombre_responsable'] ?? $activo->nombre_responsable,
            'fecha_compra' => $fila['fecha_compra'] ?? $activo->fecha_compra,
            'nro_compra' => $fila['nro_compra'] ?? $activo->nro_compra,
            'vida_util' => $fila['vida_util'] ?? $activo->vida_util,
            'estado' => $fila['estado'],
            'modelo' => $fila['modelo'] ?? $activo->modelo,
            'marca' => $fila['marca'] ?? $activo->marca,
            'serial' => $fila['serial'] ?? $activo->serial,
            'fecha_depreciacion' => $fila['fecha_depreciacion'] ?? $activo->fecha_depreciacion,
            'valor_compra' => $fila['valor_compra'] ?? $activo->valor_compra,
            'valor_depreciacion' => $fila['valor_depreciacion'] ?? $activo->valor_depreciacion,
            'ubicacion' => $fila['ubicacion'] ?? $activo->ubicacion,
            'recurso' => $fila['recurso'] ?? $activo->recurso,
            'tipo_adquisicion' => $fila['tipo_adquisicion'] ?? $activo->tipo_adquisicion,
            'observacion' => $fila['observacion'] ?? $activo->observacion,
            'descripcion' => $fila['descripcion'] ?? $activo->descripcion,
            'tipo_hoja_vi' => $fila['tipo_hoja_vi'] ?? $activo->tipo_hoja_vi,
            'area_administrativa' => $fila['area_administrativa'] ?? $activo->area_administrativa,
            'valor_residual' => $fila['valor_residual'] ?? $activo->valor_residual,
            'fecha_creacion' => $fila['fecha_creacion'] ?? $activo->fecha_creacion,
            'grupo_articulo' => $fila['grupo_articulo'] ?? $activo->grupo_articulo,
            'fecha_depre' => $fila['fecha_depre'] ?? $activo->fecha_depre,
            't_adquisicion' => $fila['t_adquisicion'] ?? $activo->t_adquisicion,
            'tipo_hoja' => $fila['tipo_hoja'] ?? $activo->tipo_hoja,
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
            [
                'codigo', 'nombre', 'codigo_grupo_articulo', 'codigo_grupo_contable', 'tipo_bien', 'codigo_servicio',
                'codigo_responsable', 'nombre_responsable', 'fecha_compra', 'nro_compra', 'vida_util', 'estado',
                'modelo', 'marca', 'serial', 'fecha_depreciacion', 'valor_compra', 'valor_depreciacion',
                'ubicacion', 'recurso', 'tipo_adquisicion', 'observacion', 'descripcion', 'tipo_hoja_vi',
                'area_administrativa', 'valor_residual', 'fecha_creacion', 'grupo_articulo', 'fecha_depre',
                't_adquisicion', 'tipo_hoja'
            ],
            [
                'ACT001', 'Laptop Dell', 'GA001', 'GC001', 'Equipo', 'CS001',
                '60258814', 'MARTHA PARADA', '2024-01-15', 'COMP001', '5', 'bueno',
                'Inspiron 15', 'Dell', 'DL123456', '2024-01-15', '1500.00', '300.00',
                'Oficina Principal', 'REC001', 'Compra', 'Laptop para desarrollo', 'Laptop Dell Inspiron 15 pulgadas', 'VI001',
                'Administración', '150.00', '2024-01-15', 'Grupo A', '2024-01-15',
                'Compra Directa', 'Hoja 1'
            ],
            [
                'ACT002', 'Monitor Samsung', 'GA002', 'GC002', 'Equipo', 'CS002',
                '60261692', 'Ana Isabel Camacho', '2024-01-20', 'COMP002', '3', 'bueno',
                '24 pulgadas', 'Samsung', 'SM789012', '2024-01-20', '300.00', '60.00',
                'Oficina Principal', 'REC002', 'Compra', 'Monitor para oficina', 'Monitor Samsung 24 pulgadas Full HD', 'VI002',
                'Administración', '30.00', '2024-01-20', 'Grupo B', '2024-01-20',
                'Compra Directa', 'Hoja 2'
            ],
            [
                'ACT003', 'Impresora HP', 'GA003', 'GC003', 'Equipo', 'CS003',
                '60250586', 'Anais Quintana Diaz', '2024-01-25', 'COMP003', '4', 'bueno',
                'LaserJet Pro', 'HP', 'HP987654', '2024-01-25', '200.00', '40.00',
                'Oficina Secundaria', 'REC003', 'Compra', 'Impresora para oficina', 'Impresora HP LaserJet Pro', 'VI003',
                'Administración', '20.00', '2024-01-25', 'Grupo C', '2024-01-25',
                'Compra Directa', 'Hoja 3'
            ],
            [
                'ACT004', 'Teclado Logitech', 'GA004', 'GC004', 'Equipo', 'CS004',
                '60254588', 'CAICEDO CARRILLO BLANCA ESTELLA', '2024-01-30', 'COMP004', '2', 'bueno',
                'K120', 'Logitech', 'LG456789', '2024-01-30', '50.00', '10.00',
                'Oficina Principal', 'REC004', 'Compra', 'Teclado para oficina', 'Teclado Logitech K120 USB', 'VI004',
                'Administración', '5.00', '2024-01-30', 'Grupo D', '2024-01-30',
                'Compra Directa', 'Hoja 4'
            ],
            [
                'ACT005', 'Mouse Microsoft', 'GA005', 'GC005', 'Equipo', 'CS005',
                '60258755', 'clara ines', '2024-02-01', 'COMP005', '2', 'bueno',
                'Basic Optical', 'Microsoft', 'MS789123', '2024-02-01', '30.00', '6.00',
                'Oficina Principal', 'REC005', 'Compra', 'Mouse para oficina', 'Mouse Microsoft Basic Optical', 'VI005',
                'Administración', '3.00', '2024-02-01', 'Grupo E', '2024-02-01',
                'Compra Directa', 'Hoja 5'
            ]
        ];

        try {
            // Asegurar que el directorio existe
            $directorio = storage_path('app/plantillas');
            if (!is_dir($directorio)) {
                mkdir($directorio, 0755, true);
            }
            
            // Crear archivo Excel usando PhpSpreadsheet
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Agregar encabezados
            $col = 1;
            foreach ($plantilla[0] as $encabezado) {
                $sheet->setCellValueByColumnAndRow($col, 1, $encabezado);
                $col++;
            }
            
            // Agregar datos
            $row = 2;
            foreach (array_slice($plantilla, 1) as $fila) {
                $col = 1;
                foreach ($fila as $valor) {
                    $sheet->setCellValueByColumnAndRow($col, $row, $valor);
                    $col++;
                }
                $row++;
            }
            
            // Guardar como Excel
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $rutaCompleta = storage_path('app/plantillas/plantilla_activos.xlsx');
            $writer->save($rutaCompleta);
            
            // Verificar que el archivo se creó correctamente
            if (!file_exists($rutaCompleta)) {
                throw new \Exception('No se pudo crear el archivo de plantilla');
            }
            
            Log::info('Plantilla creada exitosamente con ' . count($plantilla[0]) . ' campos');
        } catch (\Exception $e) {
            Log::error('Error al crear plantilla: ' . $e->getMessage());
            throw $e;
        }
    }
}

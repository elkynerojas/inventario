<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $categoria = $request->get('categoria', 'general');
        
        $configuraciones = Configuracion::where('categoria', $categoria)
            ->orderBy('orden')
            ->orderBy('nombre')
            ->paginate(20);

        // Obtener todas las categorías para el filtro
        $categorias = Configuracion::select('categoria')
            ->distinct()
            ->orderBy('categoria')
            ->pluck('categoria');

        return view('configuraciones.index', compact('configuraciones', 'categorias', 'categoria'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tipos = [
            'string' => 'Texto',
            'number' => 'Número',
            'integer' => 'Entero',
            'float' => 'Decimal',
            'boolean' => 'Verdadero/Falso',
            'json' => 'JSON',
            'file' => 'Archivo',
        ];

        $categorias = [
            'general' => 'General',
            'colegio' => 'Colegio',
            'roles' => 'Roles y Permisos',
            'sistema' => 'Sistema',
            'reportes' => 'Reportes',
            'email' => 'Email',
            'backup' => 'Respaldo',
        ];

        return view('configuraciones.create', compact('tipos', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'clave' => 'required|string|max:100|unique:configuraciones,clave',
            'categoria' => 'required|string|max:50',
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'valor' => 'nullable',
            'tipo' => 'required|string|in:string,number,integer,float,boolean,json,file',
            'opciones' => 'nullable|array',
            'requerido' => 'boolean',
            'activo' => 'boolean',
            'orden' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        
        // Procesar archivo si es tipo file
        if ($request->tipo === 'file' && $request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $ruta = $archivo->storeAs('configuraciones', $nombreArchivo, 'public');
            $data['valor'] = $ruta;
        }

        // Procesar valor según el tipo
        $data['valor'] = $this->procesarValor($data['valor'], $data['tipo']);

        Configuracion::create($data);

        // Limpiar cache
        Configuracion::limpiarCache();

        return redirect()->route('configuraciones.index')
            ->with('success', 'Configuración creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Configuracion $configuracione): View
    {
        return view('configuraciones.show', ['configuracion' => $configuracione]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Configuracion $configuracione): View
    {
        $tipos = [
            'string' => 'Texto',
            'number' => 'Número',
            'integer' => 'Entero',
            'float' => 'Decimal',
            'boolean' => 'Verdadero/Falso',
            'json' => 'JSON',
            'file' => 'Archivo',
        ];

        $categorias = [
            'general' => 'General',
            'colegio' => 'Colegio',
            'roles' => 'Roles y Permisos',
            'sistema' => 'Sistema',
            'reportes' => 'Reportes',
            'email' => 'Email',
            'backup' => 'Respaldo',
        ];

        return view('configuraciones.edit', ['configuracion' => $configuracione, 'tipos' => $tipos, 'categorias' => $categorias]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Configuracion $configuracione): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'clave' => 'required|string|max:100|unique:configuraciones,clave,' . $configuracione->id,
            'categoria' => 'required|string|max:50',
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'valor' => 'nullable',
            'tipo' => 'required|string|in:string,number,integer,float,boolean,json,file',
            'opciones' => 'nullable|array',
            'requerido' => 'boolean',
            'activo' => 'boolean',
            'orden' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        
        // Procesar archivo si es tipo file
        if ($request->tipo === 'file' && $request->hasFile('archivo')) {
            // Manejo especial para el logo del colegio
            if ($configuracione->clave === 'logo_colegio') {
                $this->procesarLogoColegio($request, $configuracione, $data);
            } else {
                // Eliminar archivo anterior si existe
                if ($configuracione->valor && Storage::disk('public')->exists($configuracione->valor)) {
                    Storage::disk('public')->delete($configuracione->valor);
                }
                
                $archivo = $request->file('archivo');
                $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $ruta = $archivo->storeAs('configuraciones', $nombreArchivo, 'public');
                $data['valor'] = $ruta;
            }
        } elseif ($request->tipo === 'file' && !$request->hasFile('archivo')) {
            // Mantener el archivo actual si no se sube uno nuevo
            $data['valor'] = $configuracione->valor;
        } else {
            // Procesar valor según el tipo
            $data['valor'] = $this->procesarValor($data['valor'], $data['tipo']);
        }

        $configuracione->update($data);

        // Limpiar cache
        Configuracion::limpiarCache();

        return redirect()->route('configuraciones.index')
            ->with('success', 'Configuración actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Configuracion $configuracione): RedirectResponse
    {
        // No permitir eliminar configuraciones requeridas
        if ($configuracione->requerido) {
            return redirect()->route('configuraciones.index')
                ->with('error', 'No se puede eliminar una configuración requerida.');
        }

        // Eliminar archivo si es tipo file
        if ($configuracione->tipo === 'file' && $configuracione->valor && Storage::disk('public')->exists($configuracione->valor)) {
            Storage::disk('public')->delete($configuracione->valor);
        }

        $configuracione->delete();

        // Limpiar cache
        Configuracion::limpiarCache();

        return redirect()->route('configuraciones.index')
            ->with('success', 'Configuración eliminada exitosamente.');
    }

    /**
     * Actualizar múltiples configuraciones de una categoría
     */
    public function actualizarCategoria(Request $request, string $categoria): RedirectResponse
    {
        $configuraciones = Configuracion::where('categoria', $categoria)->get();
        
        foreach ($configuraciones as $config) {
            $valor = $request->input($config->clave);
            
            if ($valor !== null) {
                $valorProcesado = $this->procesarValor($valor, $config->tipo);
                $config->update(['valor' => $valorProcesado]);
            }
        }

        // Limpiar cache
        Configuracion::limpiarCache();

        return redirect()->route('configuraciones.index', ['categoria' => $categoria])
            ->with('success', 'Configuraciones de la categoría actualizadas exitosamente.');
    }

    /**
     * Restaurar configuraciones por defecto
     */
    public function restaurarDefecto(): RedirectResponse
    {
        // Aquí se pueden restaurar configuraciones por defecto
        // Por ahora solo limpiamos el cache
        Configuracion::limpiarCache();

        return redirect()->route('configuraciones.index')
            ->with('success', 'Configuraciones restauradas a valores por defecto.');
    }

    /**
     * Exportar configuraciones
     */
    public function exportar(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $configuraciones = Configuracion::orderBy('categoria')->orderBy('orden')->get();
        
        $filename = 'configuraciones_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->streamDownload(function () use ($configuraciones) {
            echo json_encode($configuraciones->toArray(), JSON_PRETTY_PRINT);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Importar configuraciones
     */
    public function importar(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'archivo' => 'required|file|mimes:json',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $contenido = file_get_contents($request->file('archivo')->getPathname());
            $configuraciones = json_decode($contenido, true);

            if (!is_array($configuraciones)) {
                throw new \Exception('El archivo no contiene configuraciones válidas.');
            }

            foreach ($configuraciones as $config) {
                Configuracion::updateOrCreate(
                    ['clave' => $config['clave']],
                    $config
                );
            }

            // Limpiar cache
            Configuracion::limpiarCache();

            return redirect()->route('configuraciones.index')
                ->with('success', 'Configuraciones importadas exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al importar configuraciones: ' . $e->getMessage());
        }
    }

    /**
     * Procesar valor según el tipo
     */
    private function procesarValor($valor, $tipo)
    {
        if (is_null($valor)) {
            return null;
        }

        switch ($tipo) {
            case 'boolean':
                return filter_var($valor, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
            case 'number':
            case 'integer':
                return is_numeric($valor) ? (string) $valor : '0';
            case 'float':
                return is_numeric($valor) ? (string) $valor : '0.0';
            case 'json':
                if (is_string($valor)) {
                    json_decode($valor);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return $valor;
                    }
                }
                return json_encode($valor);
            default:
                return (string) $valor;
        }
    }

    /**
     * Obtener URL de archivo de configuración
     */
    public static function obtenerUrlArchivo($rutaArchivo)
    {
        if (!$rutaArchivo) {
            return null;
        }

        // Si es una ruta relativa que empieza con 'images/', usar asset()
        if (str_starts_with($rutaArchivo, 'images/')) {
            return asset($rutaArchivo);
        }

        // Si es una ruta de storage, usar Storage::url()
        if (Storage::disk('public')->exists($rutaArchivo)) {
            return Storage::url($rutaArchivo);
        }

        return null;
    }

    /**
     * Procesar la subida del logo del colegio
     */
    private function procesarLogoColegio(Request $request, Configuracion $configuracion, array &$data)
    {
        $archivo = $request->file('archivo');
        
        // Validar que sea una imagen
        $request->validate([
            'archivo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        
        // Crear directorio si no existe
        $directorioLogos = public_path('images/logos');
        if (!file_exists($directorioLogos)) {
            mkdir($directorioLogos, 0755, true);
        }
        
        // Eliminar archivo anterior si existe
        if ($configuracion->valor) {
            $rutaAnterior = $this->obtenerRutaCompletaLogo($configuracion->valor);
            if ($rutaAnterior && file_exists($rutaAnterior)) {
                unlink($rutaAnterior);
            }
        }
        
        // Generar nombre único para el archivo
        $extension = $archivo->getClientOriginalExtension();
        $nombreArchivo = 'logo-colegio-actual.' . $extension;
        $rutaCompleta = $directorioLogos . '/' . $nombreArchivo;
        
        // Mover el archivo
        $archivo->move($directorioLogos, $nombreArchivo);
        
        // Guardar la ruta relativa en la configuración
        $data['valor'] = 'images/logos/' . $nombreArchivo;
    }

    /**
     * Obtener la ruta completa del logo para eliminación
     */
    private function obtenerRutaCompletaLogo($rutaRelativa)
    {
        if (str_starts_with($rutaRelativa, 'images/logos/')) {
            return public_path($rutaRelativa);
        }
        
        if (str_starts_with($rutaRelativa, 'configuraciones/')) {
            return storage_path('app/public/' . $rutaRelativa);
        }
        
        return null;
    }
}
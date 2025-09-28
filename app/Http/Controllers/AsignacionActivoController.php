<?php

namespace App\Http\Controllers;

use App\Models\AsignacionActivo;
use App\Models\Activo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class AsignacionActivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AsignacionActivo::with(['activo', 'usuario', 'asignadoPor']);

        // Si el usuario no es administrador, solo mostrar sus asignaciones
        if (!auth()->user()->esAdmin()) {
            $query->where('user_id', auth()->id());
        }

        // Filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('documento_usuario')) {
            $query->whereHas('usuario', function($q) use ($request) {
                $q->where('documento', 'LIKE', "%{$request->documento_usuario}%");
            });
        }

        if ($request->filled('buscar_activo')) {
            $query->whereHas('activo', function($q) use ($request) {
                $q->where('codigo', 'LIKE', "%{$request->buscar_activo}%")
                  ->orWhere('nombre', 'LIKE', "%{$request->buscar_activo}%");
            });
        }

        if ($request->filled('fecha_desde')) {
            $query->where('fecha_asignacion', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_asignacion', '<=', $request->fecha_hasta);
        }

        $asignaciones = $query->orderBy('fecha_asignacion', 'desc')->paginate(15);

        $usuarios = User::orderBy('name')->get();
        $activos = Activo::where('estado', '!=', 'dado de baja')->orderBy('nombre')->get();
        $estadisticas = AsignacionActivo::obtenerEstadisticas();

        // Si no hay asignaciones, crear datos de ejemplo
        if ($asignaciones->count() === 0 && !$request->hasAny(['estado', 'documento_usuario', 'buscar_activo', 'fecha_desde', 'fecha_hasta'])) {
            $this->crearDatosEjemplo();
            return redirect()->route('asignaciones.index')
                ->with('success', 'Se han creado datos de ejemplo para probar la funcionalidad.');
        }

        return view('asignaciones.index', compact('asignaciones', 'usuarios', 'activos', 'estadisticas'));
    }

    /**
     * Crear datos de ejemplo si no existen
     */
    private function crearDatosEjemplo()
    {
        // Crear roles si no existen
        $rolAdmin = \App\Models\Rol::firstOrCreate(['nombre' => 'admin']);
        $rolUsuario = \App\Models\Rol::firstOrCreate(['nombre' => 'usuario']);

        // Crear usuarios si no existen
        $admin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('password'),
                'rol_id' => $rolAdmin->id,
                'documento' => '12345678',
            ]
        );

        $usuario = User::firstOrCreate(
            ['email' => 'usuario@test.com'],
            [
                'name' => 'Usuario Prueba',
                'password' => bcrypt('password'),
                'rol_id' => $rolUsuario->id,
                'documento' => '87654321',
            ]
        );

        // Crear activos si no existen
        $activo1 = Activo::firstOrCreate(
            ['codigo' => 'ACT001'],
            [
                'nombre' => 'Laptop Dell Inspiron',
                'marca' => 'Dell',
                'modelo' => 'Inspiron 15',
                'serial' => 'DL001234',
                'estado' => 'bueno',
                'valor_compra' => 1500.00,
                'fecha_compra' => now()->subDays(30),
            ]
        );

        $activo2 = Activo::firstOrCreate(
            ['codigo' => 'ACT002'],
            [
                'nombre' => 'Monitor Samsung',
                'marca' => 'Samsung',
                'modelo' => '24" LED',
                'serial' => 'SM002345',
                'estado' => 'bueno',
                'valor_compra' => 300.00,
                'fecha_compra' => now()->subDays(15),
            ]
        );

        // Crear asignaciones de ejemplo
        AsignacionActivo::create([
            'activo_id' => $activo1->id,
            'user_id' => $usuario->id,
            'asignado_por' => $admin->id,
            'fecha_asignacion' => now()->subDays(5),
            'estado' => 'activa',
            'observaciones' => 'Asignación para trabajo remoto',
            'ubicacion_asignada' => 'Oficina Principal',
        ]);

        AsignacionActivo::create([
            'activo_id' => $activo2->id,
            'user_id' => $usuario->id,
            'asignado_por' => $admin->id,
            'fecha_asignacion' => now()->subDays(20),
            'fecha_devolucion' => now()->subDays(2),
            'estado' => 'devuelta',
            'observaciones' => 'Asignación completada exitosamente',
            'ubicacion_asignada' => 'Laboratorio A',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $usuarios = User::orderBy('name')->get();
        $activosDisponibles = Activo::where('estado', '!=', 'dado de baja')
            ->whereDoesntHave('asignacionActiva')
            ->orderBy('nombre')
            ->get();

        return view('asignaciones.create', compact('usuarios', 'activosDisponibles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'activo_id' => 'required|exists:activos,id',
            'user_id' => 'required|exists:users,id',
            'fecha_asignacion' => 'required|date',
            'observaciones' => 'nullable|string|max:1000',
            'ubicacion_asignada' => 'nullable|string|max:200',
        ], [
            'activo_id.required' => 'Debe seleccionar un activo.',
            'activo_id.exists' => 'El activo seleccionado no existe.',
            'user_id.required' => 'Debe seleccionar un usuario.',
            'user_id.exists' => 'El usuario seleccionado no existe.',
            'fecha_asignacion.required' => 'La fecha de asignación es obligatoria.',
            'fecha_asignacion.date' => 'La fecha de asignación debe ser una fecha válida.',
        ]);

        // Verificar que el activo esté disponible
        $activo = Activo::findOrFail($request->activo_id);
        if (!$activo->estaDisponible()) {
            return back()->withErrors(['activo_id' => 'El activo seleccionado no está disponible para asignación.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $asignacion = AsignacionActivo::create([
                'activo_id' => $request->activo_id,
                'user_id' => $request->user_id,
                'asignado_por' => Auth::id(),
                'fecha_asignacion' => $request->fecha_asignacion,
                'estado' => 'activa',
                'observaciones' => $request->observaciones,
                'ubicacion_asignada' => $request->ubicacion_asignada,
            ]);

            DB::commit();

            return redirect()->route('asignaciones.show', $asignacion->id)
                ->with('success', 'Activo asignado exitosamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al asignar el activo. Intente nuevamente.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $asignacion = AsignacionActivo::findOrFail($id);
        $asignacion->load(['activo', 'usuario', 'asignadoPor']);
        
        // Verificar que el activo existe
        if (!$asignacion->activo) {
            // Eliminar la asignación inválida
            $asignacion->delete();
            return redirect()->route('asignaciones.index')
                ->with('error', 'La asignación contenía datos inválidos y ha sido eliminada.');
        }
        
        // Verificar que el usuario existe
        if (!$asignacion->usuario) {
            $asignacion->delete();
            return redirect()->route('asignaciones.index')
                ->with('error', 'La asignación contenía datos inválidos y ha sido eliminada.');
        }
        
        return view('asignaciones.show', compact('asignacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $asignacion = AsignacionActivo::findOrFail($id);
        
        if (!$asignacion->estaActiva()) {
            return redirect()->route('asignaciones.show', $asignacion->id)
                ->with('error', 'No se puede editar una asignación que no está activa.');
        }

        $usuarios = User::orderBy('name')->get();
        
        return view('asignaciones.edit', compact('asignacion', 'usuarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $asignacion = AsignacionActivo::findOrFail($id);
        
        if (!$asignacion->estaActiva()) {
            return redirect()->route('asignaciones.show', $asignacion->id)
                ->with('error', 'No se puede editar una asignación que no está activa.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'fecha_asignacion' => 'required|date',
            'observaciones' => 'nullable|string|max:1000',
            'ubicacion_asignada' => 'nullable|string|max:200',
        ]);

        $asignacion->update([
            'user_id' => $request->user_id,
            'fecha_asignacion' => $request->fecha_asignacion,
            'observaciones' => $request->observaciones,
            'ubicacion_asignada' => $request->ubicacion_asignada,
        ]);

        return redirect()->route('asignaciones.show', $asignacion->id)
            ->with('success', 'Asignación actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $asignacion = AsignacionActivo::findOrFail($id);
        
        if ($asignacion->estaActiva()) {
            return redirect()->route('asignaciones.show', $asignacion->id)
                ->with('error', 'No se puede eliminar una asignación activa. Debe devolverla primero.');
        }

        $asignacion->delete();

        return redirect()->route('asignaciones.index')
            ->with('success', 'Asignación eliminada exitosamente.');
    }

    /**
     * Devolver un activo asignado
     */
    public function devolver(Request $request, $id)
    {
        $asignacione = AsignacionActivo::findOrFail($id);
        
        if (!$asignacione->estaActiva()) {
            return redirect()->route('asignaciones.show', $asignacione->id)
                ->with('error', 'Esta asignación no está activa.');
        }

        $request->validate([
            'fecha_devolucion' => 'required|date',
            'observaciones_devolucion' => 'nullable|string|max:1000',
        ]);

        $asignacione->marcarComoDevuelta(
            $request->fecha_devolucion,
            $request->observaciones_devolucion
        );

        return redirect()->route('asignaciones.show', $asignacione->id)
            ->with('success', 'Activo devuelto exitosamente.');
    }

    /**
     * Marcar un activo como perdido
     */
    public function marcarPerdido(Request $request, $id)
    {
        $asignacione = AsignacionActivo::findOrFail($id);
        
        if (!$asignacione->estaActiva()) {
            return redirect()->route('asignaciones.show', $asignacione->id)
                ->with('error', 'Esta asignación no está activa.');
        }

        $request->validate([
            'observaciones_perdida' => 'required|string|max:1000',
        ], [
            'observaciones_perdida.required' => 'Las observaciones son obligatorias para marcar como perdido.',
        ]);

        $asignacione->marcarComoPerdida($request->observaciones_perdida);

        return redirect()->route('asignaciones.show', $asignacione->id)
            ->with('success', 'Activo marcado como perdido.');
    }

    /**
     * Obtener activos disponibles para asignación
     */
    public function activosDisponibles()
    {
        $activos = Activo::where('estado', '!=', 'dado de baja')
            ->whereDoesntHave('asignacionActiva')
            ->orderBy('nombre')
            ->get(['id', 'codigo', 'nombre', 'marca', 'modelo']);

        return response()->json($activos);
    }

    /**
     * Obtener asignaciones de un usuario específico
     */
    public function porUsuario(User $usuario)
    {
        $asignaciones = $usuario->asignacionesActivos()
            ->with(['activo', 'asignadoPor'])
            ->orderBy('fecha_asignacion', 'desc')
            ->paginate(15);

        return view('asignaciones.por-usuario', compact('asignaciones', 'usuario'));
    }

    /**
     * Obtener historial de un activo específico
     */
    public function historialActivo(Activo $activo)
    {
        $asignaciones = $activo->historialAsignaciones()->paginate(15);
        
        return view('asignaciones.historial-activo', compact('asignaciones', 'activo'));
    }

    /**
     * Generar acta de asignación en PDF
     */
    public function generarActa($id)
    {
        $asignacion = AsignacionActivo::findOrFail($id);
        $asignacion->load(['activo', 'usuario', 'asignadoPor']);
        
        // Verificar que el activo existe
        if (!$asignacion->activo) {
            return redirect()->route('asignaciones.show', $asignacion->id)
                ->with('error', 'No se puede generar el acta: el activo asociado no existe.');
        }
        
        // Verificar que el usuario existe
        if (!$asignacion->usuario) {
            return redirect()->route('asignaciones.show', $asignacion->id)
                ->with('error', 'No se puede generar el acta: el usuario asociado no existe.');
        }

        $data = [
            'asignacion' => $asignacion,
            'fecha_generacion' => now()->format('d/m/Y H:i'),
            'empresa' => [
                'nombre' => 'Sistema de Inventario',
                'direccion' => 'Dirección de la Empresa',
                'telefono' => 'Teléfono de la Empresa',
                'email' => 'email@empresa.com'
            ]
        ];

        $pdf = Pdf::loadView('asignaciones.acta-pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $nombreArchivo = 'Acta_Asignacion_' . $asignacion->activo->codigo . '_' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($nombreArchivo);
    }
}

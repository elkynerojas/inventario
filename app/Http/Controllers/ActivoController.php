<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class ActivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Activo::query();

        // Aplicar búsqueda general si existe
        if ($request->filled('buscar')) {
            $query->buscar($request->buscar);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por ubicación
        if ($request->filled('ubicacion')) {
            $query->where('ubicacion', $request->ubicacion);
        }

        // Filtro por responsable
        if ($request->filled('responsable')) {
            $query->where('nombre_responsable', $request->responsable);
        }

        // Filtro por rango de valor
        if ($request->filled('valor_minimo')) {
            $query->where('valor_compra', '>=', $request->valor_minimo);
        }

        if ($request->filled('valor_maximo')) {
            $query->where('valor_compra', '<=', $request->valor_maximo);
        }

        // Filtro por rango de fechas
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_compra', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_compra', '<=', $request->fecha_hasta);
        }

        $activos = $query->with(['asignacionActiva.usuario'])->orderBy('id', 'asc')->paginate(20);

        // Obtener opciones para los selects
        $ubicaciones = Activo::select('ubicacion')
            ->distinct()
            ->whereNotNull('ubicacion')
            ->orderBy('ubicacion')
            ->pluck('ubicacion');

        $responsables = Activo::select('nombre_responsable')
            ->distinct()
            ->whereNotNull('nombre_responsable')
            ->orderBy('nombre_responsable')
            ->pluck('nombre_responsable');

        return view('activos.index', compact('activos', 'ubicaciones', 'responsables'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('activos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:50|unique:activos',
            'nombre' => 'required|string|max:200',
            'estado' => 'required|in:bueno,regular,malo,dado de baja',
            'valor_compra' => 'nullable|numeric|min:0',
            'fecha_compra' => 'nullable|date',
            'marca' => 'nullable|string|max:100',
            'modelo' => 'nullable|string|max:100',
            'serial' => 'nullable|string|max:100',
            'ubicacion' => 'nullable|string|max:200',
            'nombre_responsable' => 'nullable|string|max:100',
            'tipo_bien' => 'nullable|string|max:10',
            'observacion' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'codigo_grupo_articulo' => 'nullable|string|max:50',
            'codigo_grupo_contable' => 'nullable|string|max:50',
            'codigo_servicio' => 'nullable|string|max:50',
            'codigo_responsable' => 'nullable|string|max:50',
            'nro_compra' => 'nullable|string|max:50',
            'vida_util' => 'nullable|string|max:50',
            'fecha_depreciacion' => 'nullable|date',
            'valor_depreciacion' => 'nullable|numeric|min:0',
            'recurso' => 'nullable|string|max:200',
            'tipo_adquisicion' => 'nullable|string|max:50',
            'tipo_hoja_vi' => 'nullable|string|max:50',
            'area_administrativa' => 'nullable|string|max:100',
            'valor_residual' => 'nullable|numeric|min:0',
            'grupo_articulo' => 'nullable|string|max:100',
            'fecha_depre' => 'nullable|date',
            't_adquisicion' => 'nullable|string|max:50',
            'tipo_hoja' => 'nullable|string|max:50',
        ]);

        Activo::create($validated);

        return redirect()->route('activos.index')
            ->with('success', 'Activo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Activo $activo): View
    {
        $activo->load(['asignacionActiva.usuario']);
        return view('activos.show', compact('activo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activo $activo): View
    {
        $activo->load(['asignacionActiva.usuario']);
        $activoDadoDeBaja = $activo->tieneBaja() || $activo->estado === 'dado de baja';
        return view('activos.edit', compact('activo', 'activoDadoDeBaja'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Activo $activo): RedirectResponse
    {
        // Verificar si el activo está dado de baja
        if ($activo->tieneBaja() || $activo->estado === 'dado de baja') {
            return redirect()->route('activos.show', $activo)
                ->with('error', 'No se puede editar un activo que ha sido dado de baja.');
        }

        $validated = $request->validate([
            'codigo' => 'required|string|max:50|unique:activos,codigo,' . $activo->id,
            'nombre' => 'required|string|max:200',
            'estado' => 'required|in:bueno,regular,malo,dado de baja',
            'valor_compra' => 'nullable|numeric|min:0',
            'fecha_compra' => 'nullable|date',
            'marca' => 'nullable|string|max:100',
            'modelo' => 'nullable|string|max:100',
            'serial' => 'nullable|string|max:100',
            'ubicacion' => 'nullable|string|max:200',
            'nombre_responsable' => 'nullable|string|max:100',
            'tipo_bien' => 'nullable|string|max:10',
            'observacion' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'codigo_grupo_articulo' => 'nullable|string|max:50',
            'codigo_grupo_contable' => 'nullable|string|max:50',
            'codigo_servicio' => 'nullable|string|max:50',
            'codigo_responsable' => 'nullable|string|max:50',
            'nro_compra' => 'nullable|string|max:50',
            'vida_util' => 'nullable|string|max:50',
            'fecha_depreciacion' => 'nullable|date',
            'valor_depreciacion' => 'nullable|numeric|min:0',
            'recurso' => 'nullable|string|max:200',
            'tipo_adquisicion' => 'nullable|string|max:50',
            'tipo_hoja_vi' => 'nullable|string|max:50',
            'area_administrativa' => 'nullable|string|max:100',
            'valor_residual' => 'nullable|numeric|min:0',
            'grupo_articulo' => 'nullable|string|max:100',
            'fecha_depre' => 'nullable|date',
            't_adquisicion' => 'nullable|string|max:50',
            'tipo_hoja' => 'nullable|string|max:50',
        ]);

        $activo->update($validated);

        return redirect()->route('activos.index')
            ->with('success', 'Activo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activo $activo): RedirectResponse
    {
        $activo->delete();

        return redirect()->route('activos.index')
            ->with('success', 'Activo eliminado exitosamente.');
    }

    /**
     * Obtener estadísticas para AJAX
     */
    public function estadisticas(): JsonResponse
    {
        $estadisticas = Activo::obtenerEstadisticas();
        
        return response()->json([
            'total_activos' => $estadisticas['total'],
            'activos_buenos' => $estadisticas['bueno'],
            'activos_regulares' => $estadisticas['regular'],
            'activos_malos' => $estadisticas['malo'],
        ]);
    }
}
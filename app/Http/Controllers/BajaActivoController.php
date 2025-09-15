<?php

namespace App\Http\Controllers;

use App\Models\BajaActivo;
use App\Models\Activo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class BajaActivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BajaActivo::with(['activo', 'usuario']);

        // Filtros
        if ($request->filled('motivo')) {
            $query->where('motivo', $request->motivo);
        }

        if ($request->filled('fecha_desde')) {
            $query->where('fecha_baja', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_baja', '<=', $request->fecha_hasta);
        }

        if ($request->filled('buscar_activo')) {
            $query->whereHas('activo', function($q) use ($request) {
                $q->where('codigo', 'LIKE', "%{$request->buscar_activo}%")
                  ->orWhere('nombre', 'LIKE', "%{$request->buscar_activo}%");
            });
        }

        $bajas = $query->orderBy('fecha_baja', 'desc')->paginate(15);
        $estadisticas = BajaActivo::obtenerEstadisticas();

        return view('bajas.index', compact('bajas', 'estadisticas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Activo $activo)
    {
        // Verificar que el activo existe y está disponible para baja
        if ($activo->tieneBaja()) {
            return redirect()->route('activos.show', $activo)
                ->with('error', 'Este activo ya tiene una baja registrada.');
        }

        if ($activo->estaAsignado()) {
            return redirect()->route('activos.show', $activo)
                ->with('error', 'No se puede dar de baja un activo que está asignado. Debe devolverlo primero.');
        }

        return view('bajas.create', compact('activo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Activo $activo)
    {
        $request->validate([
            'fecha_baja' => 'required|date',
            'motivo' => 'required|in:obsoleto,dañado,perdido,vendido,donado,otro',
            'descripcion_motivo' => 'required|string|max:1000',
            'valor_residual' => 'nullable|numeric|min:0',
            'destino' => 'nullable|string|max:200',
            'observaciones' => 'nullable|string|max:1000',
        ], [
            'fecha_baja.required' => 'La fecha de baja es obligatoria.',
            'fecha_baja.date' => 'La fecha de baja debe ser una fecha válida.',
            'motivo.required' => 'Debe seleccionar un motivo de baja.',
            'motivo.in' => 'El motivo seleccionado no es válido.',
            'descripcion_motivo.required' => 'La descripción del motivo es obligatoria.',
            'descripcion_motivo.max' => 'La descripción no puede exceder 1000 caracteres.',
            'valor_residual.numeric' => 'El valor residual debe ser un número válido.',
            'valor_residual.min' => 'El valor residual no puede ser negativo.',
            'destino.max' => 'El destino no puede exceder 200 caracteres.',
            'observaciones.max' => 'Las observaciones no pueden exceder 1000 caracteres.',
        ]);

        // Verificar que el activo no tenga ya una baja
        if ($activo->tieneBaja()) {
            return back()->withErrors(['error' => 'Este activo ya tiene una baja registrada.'])->withInput();
        }

        // Verificar que el activo no esté asignado
        if ($activo->estaAsignado()) {
            return back()->withErrors(['error' => 'No se puede dar de baja un activo que está asignado.'])->withInput();
        }

        DB::beginTransaction();
        try {
            // Crear la baja
            $baja = BajaActivo::create([
                'activo_id' => $activo->id,
                'usuario_id' => Auth::id(),
                'fecha_baja' => $request->fecha_baja,
                'motivo' => $request->motivo,
                'descripcion_motivo' => $request->descripcion_motivo,
                'valor_residual' => $request->valor_residual,
                'destino' => $request->destino,
                'observaciones' => $request->observaciones,
                'numero_acta' => BajaActivo::generarNumeroActa(),
            ]);

            // Actualizar el estado del activo
            $activo->update(['estado' => 'dado de baja']);

            DB::commit();

            return redirect()->route('bajas.show', $baja)
                ->with('success', 'Activo dado de baja exitosamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al procesar la baja. Intente nuevamente.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $baja = BajaActivo::findOrFail($id);
        $baja->load(['activo', 'usuario']);
        
        return view('bajas.show', compact('baja'));
    }

    /**
     * Generar acta de baja en PDF
     */
    public function generarActa($id)
    {
        $baja = BajaActivo::findOrFail($id);
        $baja->load(['activo', 'usuario']);

        $data = [
            'baja' => $baja,
            'fecha_generacion' => now()->format('d/m/Y H:i'),
            'empresa' => [
                'nombre' => 'INSTITUCIÓN EDUCATIVA [NOMBRE]',
                'direccion' => 'Dirección de la Institución Educativa',
                'telefono' => 'Teléfono de la Institución',
                'email' => 'email@institucion.edu.co'
            ]
        ];

        $pdf = Pdf::loadView('bajas.acta-pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $nombreArchivo = 'Acta_Baja_' . $baja->activo->codigo . '_' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($nombreArchivo);
    }
}

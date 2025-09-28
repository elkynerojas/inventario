<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AsignacionActivo;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class ActaAsignacionController extends Controller
{
    /**
     * Mostrar formulario para seleccionar usuario
     */
    public function index(): View
    {
        $usuarios = User::whereHas('asignacionesActivas')
            ->withCount('asignacionesActivas')
            ->orderBy('name')
            ->get();

        return view('actas.index', compact('usuarios'));
    }

    /**
     * Generar acta de asignación para un usuario específico
     */
    public function generar(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'fecha_acta' => 'required|date',
            'firmado_por' => 'required|string|max:255',
            'cargo_firmante' => 'required|string|max:255',
        ]);

        $usuario = User::with(['asignacionesActivas.activo'])
            ->findOrFail($request->user_id);

        $asignaciones = $usuario->asignacionesActivas()
            ->with(['activo'])
            ->where('estado', 'activa')
            ->orderBy('fecha_asignacion', 'desc')
            ->get();

        if ($asignaciones->isEmpty()) {
            return redirect()->back()
                ->with('error', 'El usuario seleccionado no tiene activos asignados actualmente.');
        }

        $datos = [
            'usuario' => $usuario,
            'asignaciones' => $asignaciones,
            'fecha_acta' => $request->fecha_acta,
            'firmado_por' => $request->firmado_por,
            'cargo_firmante' => $request->cargo_firmante,
            'total_activos' => $asignaciones->count(),
            'valor_total' => $asignaciones->sum(function ($asignacion) {
                return $asignacion->activo->valor_compra ?? 0;
            }),
            'colegio_nombre' => colegio_nombre(),
            'colegio_logo' => colegio_logo(),
            'colegio_direccion' => colegio_direccion(),
        ];

        $pdf = Pdf::loadView('actas.pdf', $datos);
        
        $nombreArchivo = 'acta_asignacion_' . $usuario->documento . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($nombreArchivo);
    }

    /**
     * Vista previa del acta
     */
    public function vistaPrevia(Request $request): View
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'fecha_acta' => 'required|date',
            'firmado_por' => 'required|string|max:255',
            'cargo_firmante' => 'required|string|max:255',
        ]);

        $usuario = User::with(['asignacionesActivas.activo'])
            ->findOrFail($request->user_id);

        $asignaciones = $usuario->asignacionesActivas()
            ->with(['activo'])
            ->where('estado', 'activa')
            ->orderBy('fecha_asignacion', 'desc')
            ->get();

        if ($asignaciones->isEmpty()) {
            return redirect()->back()
                ->with('error', 'El usuario seleccionado no tiene activos asignados actualmente.');
        }

        $datos = [
            'usuario' => $usuario,
            'asignaciones' => $asignaciones,
            'fecha_acta' => $request->fecha_acta,
            'firmado_por' => $request->firmado_por,
            'cargo_firmante' => $request->cargo_firmante,
            'total_activos' => $asignaciones->count(),
            'valor_total' => $asignaciones->sum(function ($asignacion) {
                return $asignacion->activo->valor_compra ?? 0;
            }),
            'colegio_nombre' => colegio_nombre(),
            'colegio_logo' => colegio_logo(),
            'colegio_direccion' => colegio_direccion(),
        ];

        return view('actas.vista-previa', $datos);
    }
}
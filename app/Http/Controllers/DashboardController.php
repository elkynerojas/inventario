<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use App\Models\AsignacionActivo;
use App\Models\BajaActivo;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $estadisticas = [
            'total_activos' => Activo::count(),
            'activos_disponibles' => Activo::where('estado', 'disponible')->count(),
            'activos_asignados' => AsignacionActivo::where('estado', 'activa')->count(),
            'activos_dados_baja' => BajaActivo::count(),
            'total_usuarios' => User::count(),
            'total_asignaciones' => AsignacionActivo::count(),
        ];

        // Actividades recientes
        $asignaciones_recientes = AsignacionActivo::with(['activo', 'usuario'])
            ->latest()
            ->limit(5)
            ->get();

        $activos_recientes = Activo::latest()
            ->limit(5)
            ->get();

        // Estadísticas por estado
        $estadisticas_por_estado = Activo::selectRaw('estado, COUNT(*) as cantidad')
            ->groupBy('estado')
            ->pluck('cantidad', 'estado')
            ->toArray();

        // Estadísticas de asignaciones por mes (últimos 6 meses)
        $asignaciones_por_mes = AsignacionActivo::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as mes, COUNT(*) as cantidad')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('cantidad', 'mes')
            ->toArray();

        return view('dashboard', compact(
            'estadisticas',
            'asignaciones_recientes',
            'activos_recientes',
            'estadisticas_por_estado',
            'asignaciones_por_mes'
        ));
    }
}

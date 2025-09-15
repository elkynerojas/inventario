<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use App\Models\AsignacionActivo;
use App\Models\BajaActivo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    /**
     * Mostrar página de reportes
     */
    public function index(Request $request): View
    {
        $query = Activo::query();

        // Aplicar filtros específicos
        if ($request->filled('filtro') && $request->filled('parametro')) {
            switch ($request->filtro) {
                case 'estado':
                    $query->porEstado($request->parametro);
                    break;
                case 'ubicacion':
                    $query->porUbicacion($request->parametro);
                    break;
                case 'responsable':
                    $query->porResponsable($request->parametro);
                    break;
            }
        }

        $activos = $query->orderBy('id', 'desc')->get();
        $estadisticas = Activo::obtenerEstadisticas();

        return view('reportes.index', compact('activos', 'estadisticas'));
    }

    /**
     * Generar reporte en Excel
     */
    public function excel(Request $request)
    {
        $query = Activo::query();

        // Aplicar filtros específicos
        if ($request->filled('filtro') && $request->filled('parametro')) {
            switch ($request->filtro) {
                case 'estado':
                    $query->porEstado($request->parametro);
                    break;
                case 'ubicacion':
                    $query->porUbicacion($request->parametro);
                    break;
                case 'responsable':
                    $query->porResponsable($request->parametro);
                    break;
            }
        }

        $activos = $query->orderBy('id', 'desc')->get();

        return Excel::download(new ActivosExport($activos), 'reporte_activos_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    /**
     * Generar reporte en PDF
     */
    public function pdf(Request $request)
    {
        $query = Activo::query();

        // Aplicar filtros específicos
        if ($request->filled('filtro') && $request->filled('parametro')) {
            switch ($request->filtro) {
                case 'estado':
                    $query->porEstado($request->parametro);
                    break;
                case 'ubicacion':
                    $query->porUbicacion($request->parametro);
                    break;
                case 'responsable':
                    $query->porResponsable($request->parametro);
                    break;
            }
        }

        // Limitar la cantidad de registros para evitar problemas de memoria
        $activos = $query->orderBy('id', 'desc')->limit(500)->get();
        $estadisticas = Activo::obtenerEstadisticas();
        
        // Calcular totales
        $totalValor = $activos->sum('valor_compra');
        $filtroAplicado = $request->filled('filtro') && $request->filled('parametro') 
            ? ucfirst($request->filtro) . ': ' . $request->parametro 
            : 'Todos los activos';

        // Configurar opciones de PDF para optimizar memoria
        $pdf = Pdf::loadView('reportes.pdf', compact('activos', 'estadisticas', 'totalValor', 'filtroAplicado'));
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'defaultFont' => 'Arial'
        ]);
        
        return $pdf->download('reporte_activos_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Mostrar estadísticas detalladas del sistema
     */
    public function estadisticas(): View
    {
        // Estadísticas básicas
        $estadisticas = [
            'total_activos' => Activo::count(),
            'activos_disponibles' => Activo::where('estado', 'disponible')->count(),
            'activos_asignados' => AsignacionActivo::where('estado', 'activa')->count(),
            'activos_dados_baja' => BajaActivo::count(),
            'total_usuarios' => User::count(),
            'total_asignaciones' => AsignacionActivo::count(),
            'total_bajas' => BajaActivo::count(),
        ];

        // Estadísticas por estado
        $estadisticas['por_estado'] = Activo::selectRaw('estado, COUNT(*) as cantidad')
            ->groupBy('estado')
            ->pluck('cantidad', 'estado')
            ->toArray();

        // Estadísticas por ubicación
        $estadisticas['por_ubicacion'] = Activo::selectRaw('ubicacion, COUNT(*) as cantidad')
            ->groupBy('ubicacion')
            ->orderByDesc('cantidad')
            ->limit(10)
            ->pluck('cantidad', 'ubicacion')
            ->toArray();

        // Top ubicaciones con porcentajes
        $totalActivos = $estadisticas['total_activos'];
        $topUbicaciones = Activo::selectRaw('ubicacion, COUNT(*) as cantidad')
            ->groupBy('ubicacion')
            ->orderByDesc('cantidad')
            ->limit(10)
            ->get()
            ->map(function ($item) use ($totalActivos) {
                return [
                    'ubicacion' => $item->ubicacion,
                    'cantidad' => $item->cantidad,
                    'porcentaje' => $totalActivos > 0 ? ($item->cantidad / $totalActivos) * 100 : 0
                ];
            });

        $estadisticas['top_ubicaciones'] = $topUbicaciones;

        // Top responsables con porcentajes
        $topResponsables = Activo::selectRaw('nombre_responsable, COUNT(*) as cantidad')
            ->whereNotNull('nombre_responsable')
            ->groupBy('nombre_responsable')
            ->orderByDesc('cantidad')
            ->limit(10)
            ->get()
            ->map(function ($item) use ($totalActivos) {
                return [
                    'responsable' => $item->nombre_responsable,
                    'cantidad' => $item->cantidad,
                    'porcentaje' => $totalActivos > 0 ? ($item->cantidad / $totalActivos) * 100 : 0
                ];
            });

        $estadisticas['top_responsables'] = $topResponsables;

        // Estadísticas financieras
        $estadisticas['valor_total'] = Activo::sum('valor_compra');
        $estadisticas['valor_promedio'] = $estadisticas['total_activos'] > 0 
            ? $estadisticas['valor_total'] / $estadisticas['total_activos'] 
            : 0;

        // Valor de activos asignados
        $activosAsignados = AsignacionActivo::where('estado', 'activa')
            ->with('activo')
            ->get()
            ->pluck('activo.valor_compra')
            ->filter()
            ->sum();
        $estadisticas['valor_asignado'] = $activosAsignados;

        // Valor de activos disponibles
        $activosDisponibles = Activo::where('estado', 'disponible')->sum('valor_compra');
        $estadisticas['valor_disponible'] = $activosDisponibles;

        // Asignaciones por mes (últimos 12 meses)
        $asignacionesPorMes = AsignacionActivo::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as mes, COUNT(*) as cantidad')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('cantidad', 'mes')
            ->toArray();

        $estadisticas['asignaciones_por_mes'] = $asignacionesPorMes;

        // Bajas por motivo
        $estadisticas['bajas_por_motivo'] = BajaActivo::selectRaw('motivo, COUNT(*) as cantidad')
            ->groupBy('motivo')
            ->pluck('cantidad', 'motivo')
            ->toArray();

        // Actividades recientes (últimas 10)
        $actividadesRecientes = collect();

        // Agregar asignaciones recientes
        $asignacionesRecientes = AsignacionActivo::with(['activo', 'usuario'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($asignacion) {
                return [
                    'descripcion' => 'Nueva asignación',
                    'detalle' => $asignacion->activo->nombre ?? 'Activo eliminado' . ' asignado a ' . ($asignacion->usuario->name ?? 'Usuario eliminado'),
                    'fecha' => $asignacion->created_at ? $asignacion->created_at->diffForHumans() : 'Fecha no disponible'
                ];
            });

        // Agregar activos recientes
        $activosRecientes = Activo::latest()
            ->limit(5)
            ->get()
            ->map(function ($activo) {
                return [
                    'descripcion' => 'Nuevo activo',
                    'detalle' => $activo->nombre . ' (' . $activo->codigo . ')',
                    'fecha' => $activo->created_at ? $activo->created_at->diffForHumans() : 'Fecha no disponible'
                ];
            });

        // Agregar bajas recientes
        $bajasRecientes = BajaActivo::with(['activo'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($baja) {
                return [
                    'descripcion' => 'Baja de activo',
                    'detalle' => $baja->activo->nombre ?? 'Activo eliminado' . ' - Motivo: ' . $baja->motivo,
                    'fecha' => $baja->created_at ? $baja->created_at->diffForHumans() : 'Fecha no disponible'
                ];
            });

        // Combinar todas las actividades y ordenar por fecha
        $actividadesRecientes = $asignacionesRecientes
            ->concat($activosRecientes)
            ->concat($bajasRecientes)
            ->sortByDesc('fecha')
            ->take(10);

        $estadisticas['actividades_recientes'] = $actividadesRecientes;

        return view('reportes.estadisticas', compact('estadisticas'));
    }
}

class ActivosExport implements FromCollection, WithHeadings
{
    protected $activos;

    public function __construct($activos)
    {
        $this->activos = $activos;
    }

    public function collection()
    {
        return $this->activos->map(function ($activo) {
            return [
                $activo->codigo,
                $activo->nombre,
                $activo->estado,
                $activo->ubicacion,
                $activo->nombre_responsable,
                '$' . number_format($activo->valor_compra, 2),
                $activo->fecha_compra ? $activo->fecha_compra->format('d/m/Y') : 'N/A',
                $activo->marca ?: 'No especificada',
                $activo->modelo ?: 'No especificado',
                $activo->serial ?: 'No especificado',
                $activo->tipo_bien,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Código',
            'Nombre',
            'Estado',
            'Ubicación',
            'Responsable',
            'Valor',
            'Fecha Compra',
            'Marca',
            'Modelo',
            'Serial',
            'Tipo',
        ];
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Activo;
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
     * Obtener estadísticas para AJAX
     */
    public function estadisticas()
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
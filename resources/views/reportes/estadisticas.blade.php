@extends('layouts.app')

@section('title', 'Estadísticas del Sistema')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Header de la página -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="bi bi-graph-up"></i> Estadísticas del Sistema</h2>
                    <div class="d-flex gap-2">
                        <a href="{{ route('reportes.index') }}" class="btn btn-info">
                            <i class="bi bi-file-earmark-text"></i> Reportes
                        </a>
                        <button class="btn btn-success" onclick="exportarEstadisticas()">
                            <i class="bi bi-download"></i> Exportar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Generales -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card dashboard-stat-card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">{{ $estadisticas['total_activos'] ?? 0 }}</h4>
                                <p class="card-text">Total Activos</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-box-seam dashboard-icon" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-stat-card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">{{ $estadisticas['activos_disponibles'] ?? 0 }}</h4>
                                <p class="card-text">Disponibles</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-check-circle dashboard-icon" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-stat-card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">{{ $estadisticas['activos_asignados'] ?? 0 }}</h4>
                                <p class="card-text">Asignados</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-person-check dashboard-icon" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-stat-card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">{{ $estadisticas['activos_dados_baja'] ?? 0 }}</h4>
                                <p class="card-text">Dados de Baja</p>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-trash dashboard-icon" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas por Estado -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card dashboard-card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Distribución por Estado</h5>
                    </div>
                    <div class="card-body">
                        <div class="stats-chart-container">
                            <canvas id="estadoChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card dashboard-card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Activos por Ubicación</h5>
                    </div>
                    <div class="card-body">
                        <div class="stats-chart-container">
                            <canvas id="ubicacionChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas de Asignaciones -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card dashboard-card">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0"><i class="bi bi-calendar3"></i> Asignaciones por Mes</h5>
                    </div>
                    <div class="card-body">
                        <div class="stats-chart-container">
                            <canvas id="asignacionesChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card dashboard-card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bi bi-graph-down"></i> Bajas por Motivo</h5>
                    </div>
                    <div class="card-body">
                        <div class="stats-chart-container">
                            <canvas id="bajasChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tablas Detalladas -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card dashboard-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-table"></i> Top 10 Ubicaciones</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm stats-table">
                                <thead>
                                    <tr>
                                        <th>Ubicación</th>
                                        <th>Cantidad</th>
                                        <th>Porcentaje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($estadisticas['top_ubicaciones'] ?? [] as $ubicacion)
                                    <tr>
                                        <td>{{ $ubicacion['ubicacion'] }}</td>
                                        <td>{{ $ubicacion['cantidad'] }}</td>
                                        <td>
                                            <div class="progress stats-progress">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: {{ $ubicacion['porcentaje'] }}%"
                                                     aria-valuenow="{{ $ubicacion['porcentaje'] }}" 
                                                     aria-valuemin="0" aria-valuemax="100">
                                                    {{ number_format($ubicacion['porcentaje'], 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card dashboard-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-people"></i> Top 10 Responsables</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm stats-table">
                                <thead>
                                    <tr>
                                        <th>Responsable</th>
                                        <th>Cantidad</th>
                                        <th>Porcentaje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($estadisticas['top_responsables'] ?? [] as $responsable)
                                    <tr>
                                        <td>{{ $responsable['responsable'] }}</td>
                                        <td>{{ $responsable['cantidad'] }}</td>
                                        <td>
                                            <div class="progress stats-progress">
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                     style="width: {{ $responsable['porcentaje'] }}%"
                                                     aria-valuenow="{{ $responsable['porcentaje'] }}" 
                                                     aria-valuemin="0" aria-valuemax="100">
                                                    {{ number_format($responsable['porcentaje'], 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen Financiero -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card stats-financial-card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-currency-dollar"></i> Resumen Financiero</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <h3 class="text-primary">${{ number_format($estadisticas['valor_total'] ?? 0, 2) }}</h3>
                                <p class="text-muted">Valor Total del Inventario</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <h3 class="text-success">${{ number_format($estadisticas['valor_promedio'] ?? 0, 2) }}</h3>
                                <p class="text-muted">Valor Promedio por Activo</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <h3 class="text-warning">${{ number_format($estadisticas['valor_asignado'] ?? 0, 2) }}</h3>
                                <p class="text-muted">Valor de Activos Asignados</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <h3 class="text-danger">${{ number_format($estadisticas['valor_disponible'] ?? 0, 2) }}</h3>
                                <p class="text-muted">Valor de Activos Disponibles</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actividad Reciente -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card dashboard-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Actividades Recientes</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($actividades_recientes) && $actividades_recientes->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($actividades_recientes as $actividad)
                                <div class="list-group-item activity-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $actividad['descripcion'] }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $actividad['detalle'] }}</small>
                                    </div>
                                    <small class="text-muted">{{ $actividad['fecha'] }}</small>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No hay actividades recientes</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card dashboard-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información del Sistema</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <p><strong>Total de Usuarios:</strong> {{ $estadisticas['total_usuarios'] ?? 0 }}</p>
                                <p><strong>Total de Asignaciones:</strong> {{ $estadisticas['total_asignaciones'] ?? 0 }}</p>
                                <p><strong>Total de Bajas:</strong> {{ $estadisticas['total_bajas'] ?? 0 }}</p>
                            </div>
                            <div class="col-6">
                                <p><strong>Última Actualización:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                                <p><strong>Versión del Sistema:</strong> 1.0.0</p>
                                <p><strong>Estado del Sistema:</strong> <span class="badge bg-success">Activo</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts para gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfico de distribución por estado
const estadoCtx = document.getElementById('estadoChart').getContext('2d');
new Chart(estadoCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(array_keys($estadisticas['por_estado'] ?? [])) !!},
        datasets: [{
            data: {!! json_encode(array_values($estadisticas['por_estado'] ?? [])) !!},
            backgroundColor: [
                '#28a745',
                '#ffc107',
                '#dc3545',
                '#6c757d',
                '#17a2b8'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Gráfico de activos por ubicación
const ubicacionCtx = document.getElementById('ubicacionChart').getContext('2d');
new Chart(ubicacionCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_keys($estadisticas['por_ubicacion'] ?? [])) !!},
        datasets: [{
            label: 'Cantidad de Activos',
            data: {!! json_encode(array_values($estadisticas['por_ubicacion'] ?? [])) !!},
            backgroundColor: '#17a2b8'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Gráfico de asignaciones por mes
const asignacionesCtx = document.getElementById('asignacionesChart').getContext('2d');
new Chart(asignacionesCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_keys($estadisticas['asignaciones_por_mes'] ?? [])) !!},
        datasets: [{
            label: 'Asignaciones',
            data: {!! json_encode(array_values($estadisticas['asignaciones_por_mes'] ?? [])) !!},
            borderColor: '#ffc107',
            backgroundColor: 'rgba(255, 193, 7, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Gráfico de bajas por motivo
const bajasCtx = document.getElementById('bajasChart').getContext('2d');
new Chart(bajasCtx, {
    type: 'pie',
    data: {
        labels: {!! json_encode(array_keys($estadisticas['bajas_por_motivo'] ?? [])) !!},
        datasets: [{
            data: {!! json_encode(array_values($estadisticas['bajas_por_motivo'] ?? [])) !!},
            backgroundColor: [
                '#dc3545',
                '#fd7e14',
                '#6f42c1',
                '#20c997',
                '#e83e8c',
                '#6c757d'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Función para exportar estadísticas
function exportarEstadisticas() {
    // Crear un objeto con todas las estadísticas
    const estadisticas = {
        fecha: new Date().toLocaleString(),
        total_activos: {{ $estadisticas['total_activos'] ?? 0 }},
        activos_disponibles: {{ $estadisticas['activos_disponibles'] ?? 0 }},
        activos_asignados: {{ $estadisticas['activos_asignados'] ?? 0 }},
        activos_dados_baja: {{ $estadisticas['activos_dados_baja'] ?? 0 }},
        valor_total: {{ $estadisticas['valor_total'] ?? 0 }},
        valor_promedio: {{ $estadisticas['valor_promedio'] ?? 0 }},
        por_estado: {!! json_encode($estadisticas['por_estado'] ?? []) !!},
        por_ubicacion: {!! json_encode($estadisticas['por_ubicacion'] ?? []) !!}
    };
    
    // Crear y descargar el archivo JSON
    const dataStr = JSON.stringify(estadisticas, null, 2);
    const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
    
    const exportFileDefaultName = 'estadisticas_sistema_' + new Date().toISOString().slice(0,10) + '.json';
    
    const linkElement = document.createElement('a');
    linkElement.setAttribute('href', dataUri);
    linkElement.setAttribute('download', exportFileDefaultName);
    linkElement.click();
}
</script>
@endsection

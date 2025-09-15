@extends('layouts.app')

@section('title', 'Dashboard - Sistema de Inventario')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Header del Dashboard -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="bi bi-speedometer2"></i> Dashboard del Sistema</h2>
                    <div class="text-muted">
                        <i class="bi bi-calendar3"></i> {{ now()->format('d/m/Y H:i') }}
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

        <!-- Funcionalidades Principales -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card dashboard-card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-box-seam"></i> Gestión de Activos</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Administra el inventario de activos del sistema</p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('activos.index') }}" class="btn btn-primary">
                                <i class="bi bi-list-ul"></i> Ver Inventario
                            </a>
                            @if(auth()->user()->esAdmin())
                            <a href="{{ route('activos.create') }}" class="btn btn-outline-primary">
                                <i class="bi bi-plus-lg"></i> Nuevo Activo
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card dashboard-card h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-person-check"></i> Asignaciones</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Gestiona las asignaciones de activos a usuarios</p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('asignaciones.index') }}" class="btn btn-success">
                                <i class="bi bi-list-ul"></i> Ver Asignaciones
                            </a>
                            <a href="{{ route('asignaciones.create') }}" class="btn btn-outline-success">
                                <i class="bi bi-plus-lg"></i> Nueva Asignación
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Funcionalidades Secundarias -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card dashboard-card h-100">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bi bi-trash"></i> Bajas de Activos</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Procesa las bajas de activos del inventario</p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('bajas.index') }}" class="btn btn-danger">
                                <i class="bi bi-list-ul"></i> Ver Bajas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card dashboard-card h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Reportes</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Genera reportes del sistema de inventario</p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('reportes.index') }}" class="btn btn-info">
                                <i class="bi bi-file-earmark-text"></i> Ver Reportes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accesos Rápidos -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="bi bi-lightning"></i> Accesos Rápidos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('reportes.excel') }}" class="btn btn-outline-success w-100 quick-access-btn">
                                    <i class="bi bi-file-excel"></i> Exportar Excel
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('reportes.pdf') }}" class="btn btn-outline-danger w-100 quick-access-btn">
                                    <i class="bi bi-file-pdf"></i> Exportar PDF
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('reportes.estadisticas') }}" class="btn btn-outline-info w-100 quick-access-btn">
                                    <i class="bi bi-graph-up"></i> Estadísticas
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary w-100 quick-access-btn">
                                    <i class="bi bi-person-gear"></i> Mi Perfil
                                </a>
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
                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Asignaciones Recientes</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($asignaciones_recientes) && $asignaciones_recientes->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($asignaciones_recientes as $asignacion)
                                <div class="list-group-item activity-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $asignacion->activo->nombre ?? 'Activo eliminado' }}</strong>
                                        <br>
                                        <small class="text-muted">Asignado a: {{ $asignacion->usuario->name ?? 'Usuario eliminado' }}</small>
                                    </div>
                                    <small class="text-muted">{{ $asignacion->created_at ? $asignacion->created_at->diffForHumans() : 'Fecha no disponible' }}</small>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No hay asignaciones recientes</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card dashboard-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Activos Recientes</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($activos_recientes) && $activos_recientes->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($activos_recientes as $activo)
                                <div class="list-group-item activity-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $activo->nombre }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $activo->codigo }} - {{ $activo->estado }}</small>
                                    </div>
                                    <small class="text-muted">{{ $activo->created_at ? $activo->created_at->diffForHumans() : 'Fecha no disponible' }}</small>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No hay activos recientes</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
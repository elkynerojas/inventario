@extends('layouts.app')

@section('title', 'Detalles de Importación')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>Detalles de Importación
                </h1>
                <a href="{{ route('importaciones.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Información general -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2"></i>Información General
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">ID:</td>
                                            <td>{{ $importacion->id }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Archivo:</td>
                                            <td>
                                                <i class="bi bi-file-excel text-success me-2"></i>
                                                {{ $importacion->nombre_archivo }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Estado:</td>
                                            <td>
                                                @switch($importacion->estado)
                                                    @case('pendiente')
                                                        <span class="badge bg-warning">Pendiente</span>
                                                        @break
                                                    @case('procesando')
                                                        <span class="badge bg-info">Procesando</span>
                                                        @break
                                                    @case('completado')
                                                        <span class="badge bg-success">Completado</span>
                                                        @break
                                                    @case('fallido')
                                                        <span class="badge bg-danger">Fallido</span>
                                                        @break
                                                @endswitch
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Usuario:</td>
                                            <td>{{ $importacion->usuario->name ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Fecha de Subida:</td>
                                            <td>{{ $importacion->created_at ? $importacion->created_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Última Actualización:</td>
                                            <td>{{ $importacion->updated_at ? $importacion->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                                        </tr>
                                        @if($importacion->observaciones && isset($importacion->observaciones['fecha_procesamiento']))
                                        <tr>
                                            <td class="fw-bold">Fecha de Procesamiento:</td>
                                            <td>{{ $importacion->observaciones['fecha_procesamiento'] }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progreso -->
                    @if($importacion->estado === 'procesando' || $importacion->estado === 'completado')
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-graph-up me-2"></i>Progreso de Importación
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="progress mb-3" style="height: 25px;">
                                        <div class="progress-bar progress-bar-striped 
                                            @if($importacion->estado === 'procesando') progress-bar-animated @endif" 
                                             role="progressbar" 
                                             style="width: {{ $importacion->porcentaje_progreso }}%"
                                             aria-valuenow="{{ $importacion->porcentaje_progreso }}" 
                                             aria-valuemin="0" aria-valuemax="100">
                                            {{ $importacion->porcentaje_progreso }}%
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <span class="badge bg-primary fs-6">
                                        {{ $importacion->registros_procesados }} / {{ $importacion->total_registros }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Estadísticas -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-bar-chart me-2"></i>Estadísticas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="border rounded p-3">
                                        <h3 class="text-primary mb-1">{{ $importacion->total_registros }}</h3>
                                        <small class="text-muted">Total Registros</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border rounded p-3">
                                        <h3 class="text-success mb-1">{{ $importacion->registros_exitosos }}</h3>
                                        <small class="text-muted">Exitosos</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border rounded p-3">
                                        <h3 class="text-danger mb-1">{{ $importacion->registros_fallidos }}</h3>
                                        <small class="text-muted">Fallidos</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border rounded p-3">
                                        <h3 class="text-info mb-1">{{ $importacion->porcentaje_progreso }}%</h3>
                                        <small class="text-muted">Progreso</small>
                                    </div>
                                </div>
                            </div>
                            
                            @if($importacion->estado === 'completado' && $importacion->registros_exitosos > 0)
                            <div class="mt-3">
                                <div class="alert alert-success">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-check-circle me-2"></i>Importación Completada
                                    </h6>
                                    <p class="mb-0">
                                        Se procesaron {{ $importacion->registros_exitosos }} activos exitosamente. 
                                        Los activos existentes se actualizaron y los nuevos se crearon. 
                                        Si algunos activos tenían el campo <strong>codigo_responsable</strong> con un número de documento válido, 
                                        se crearon o actualizaron asignaciones automáticas.
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Errores -->
                    @if($importacion->registros_fallidos > 0 && $importacion->errores)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-exclamation-triangle me-2"></i>Errores Encontrados
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Fila</th>
                                            <th>Error</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($importacion->errores as $error)
                                        <tr>
                                            <td class="fw-bold">{{ $error['fila'] }}</td>
                                            <td class="text-danger">{{ $error['error'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Panel lateral -->
                <div class="col-lg-4">
                    <!-- Acciones -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-gear me-2"></i>Acciones
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if($importacion->estado === 'procesando')
                                    <button class="btn btn-outline-info" onclick="actualizarEstado()">
                                        <i class="bi bi-arrow-clockwise me-1"></i>Actualizar Estado
                                    </button>
                                @endif
                                
                                @if($importacion->estado === 'completado')
                                    <a href="{{ route('activos.index') }}" class="btn btn-success">
                                        <i class="bi bi-eye me-1"></i>Ver Activos Importados
                                    </a>
                                @endif
                                
                                <a href="{{ route('importaciones.plantilla') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-download me-1"></i>Descargar Plantilla
                                </a>
                                
                                <a href="{{ route('importaciones.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Nueva Importación
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Información adicional -->
                    @if($importacion->observaciones)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-square me-2"></i>Observaciones
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach($importacion->observaciones as $key => $value)
                                <div class="mb-2">
                                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                    <span class="text-muted">{{ $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
const importacionId = {{ $importacion->id }};

function actualizarEstado() {
    fetch(`/importaciones/${importacionId}/estado`)
        .then(response => response.json())
        .then(data => {
            // Recargar la página para mostrar el estado actualizado
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar el estado');
        });
}

// La importación ahora se procesa de forma síncrona, no necesita auto-actualización
// Solo mostrar mensaje informativo si está completada
@if($importacion->estado === 'completado')
console.log('Importación completada exitosamente');
@elseif($importacion->estado === 'fallido')
console.log('Importación falló');
@endif
</script>
@endsection

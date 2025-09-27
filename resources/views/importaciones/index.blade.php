@extends('layouts.app')

@section('title', 'Importaciones de Activos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="bi bi-upload me-2"></i>Importaciones de Activos
                </h1>
                <a href="{{ route('importaciones.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Nueva Importación
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Tabla de importaciones -->
            <div class="card">
                <div class="card-body p-0">
                    @if($importaciones->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Archivo</th>
                                        <th>Estado</th>
                                        <th>Progreso</th>
                                        <th>Registros</th>
                                        <th>Usuario</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($importaciones as $importacion)
                                    <tr>
                                        <td>{{ $importacion->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-file-excel text-success me-2"></i>
                                                <span class="text-truncate" style="max-width: 200px;" title="{{ $importacion->nombre_archivo }}">
                                                    {{ $importacion->nombre_archivo }}
                                                </span>
                                            </div>
                                        </td>
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
                                        <td>
                                            @if($importacion->estado === 'procesando' || $importacion->estado === 'completado')
                                                <div class="progress" style="width: 100px; height: 20px;">
                                                    <div class="progress-bar" role="progressbar" 
                                                         style="width: {{ $importacion->porcentaje_progreso }}%"
                                                         aria-valuenow="{{ $importacion->porcentaje_progreso }}" 
                                                         aria-valuemin="0" aria-valuemax="100">
                                                        {{ $importacion->porcentaje_progreso }}%
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $importacion->registros_exitosos }}/{{ $importacion->total_registros }}
                                                @if($importacion->registros_fallidos > 0)
                                                    <span class="text-danger">({{ $importacion->registros_fallidos }} errores)</span>
                                                @endif
                                            </small>
                                        </td>
                                        <td>{{ $importacion->usuario->name ?? 'N/A' }}</td>
                                        <td>{{ $importacion->created_at ? $importacion->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('importaciones.show', $importacion) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if($importacion->estado === 'fallido' && $importacion->registros_fallidos > 0)
                                                    <button class="btn btn-sm btn-outline-warning" 
                                                            onclick="verErrores({{ $importacion->id }})" title="Ver errores">
                                                        <i class="bi bi-exclamation-triangle"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $importaciones->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-upload" style="font-size: 3rem; color: #6c757d;"></i>
                            <h3 class="mt-3">No hay importaciones registradas</h3>
                            <p class="text-muted">Comienza subiendo tu primer archivo Excel</p>
                            <a href="{{ route('importaciones.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>Nueva Importación
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar errores -->
<div class="modal fade" id="modalErrores" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Errores de Importación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="contenidoErrores"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function verErrores(importacionId) {
    fetch(`/importaciones/${importacionId}/estado`)
        .then(response => response.json())
        .then(data => {
            let contenido = '<div class="table-responsive"><table class="table table-sm">';
            contenido += '<thead><tr><th>Fila</th><th>Error</th></tr></thead><tbody>';
            
            if (data.errores && data.errores.length > 0) {
                data.errores.forEach(error => {
                    contenido += `<tr><td>${error.fila}</td><td class="text-danger">${error.error}</td></tr>`;
                });
            } else {
                contenido += '<tr><td colspan="2" class="text-center text-muted">No hay errores específicos</td></tr>';
            }
            
            contenido += '</tbody></table></div>';
            document.getElementById('contenidoErrores').innerHTML = contenido;
            
            const modal = new bootstrap.Modal(document.getElementById('modalErrores'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los errores');
        });
}
</script>
@endsection

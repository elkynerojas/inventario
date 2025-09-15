@extends('layouts.app')

@section('title', 'Detalles de Asignación')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle"></i> Detalles de Asignación #{{ $asignacion->id }}
                </h5>
                <div>
                    @if($asignacion->estaActiva())
                        <span class="badge bg-success fs-6">Activa</span>
                    @elseif($asignacion->fueDevuelta())
                        <span class="badge bg-info fs-6">Devuelta</span>
                    @elseif($asignacion->estaPerdida())
                        <span class="badge bg-warning fs-6">Perdida</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Información del Activo</h6>
                        @if($asignacion->activo)
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Código:</strong></td>
                                    <td>{{ $asignacion->activo->codigo }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nombre:</strong></td>
                                    <td>{{ $asignacion->activo->nombre }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Marca:</strong></td>
                                    <td>{{ $asignacion->activo->marca ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Modelo:</strong></td>
                                    <td>{{ $asignacion->activo->modelo ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Serial:</strong></td>
                                    <td>{{ $asignacion->activo->serial ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Estado:</strong></td>
                                    <td>
                                        <span class="badge-estado {{ $asignacion->activo->estado }}">
                                            {{ ucfirst($asignacion->activo->estado) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        @else
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                                El activo asociado a esta asignación no existe o ha sido eliminado.
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Información de la Asignación</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Usuario Asignado:</strong></td>
                                <td>{{ $asignacion->usuario->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $asignacion->usuario->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Fecha Asignación:</strong></td>
                                <td>{{ $asignacion->fecha_asignacion->format('d/m/Y') }}</td>
                            </tr>
                            @if($asignacion->fecha_devolucion)
                                <tr>
                                    <td><strong>Fecha Devolución:</strong></td>
                                    <td>{{ $asignacion->fecha_devolucion->format('d/m/Y') }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td><strong>Asignado Por:</strong></td>
                                <td>{{ $asignacion->asignadoPor->name }}</td>
                            </tr>
                            @if($asignacion->ubicacion_asignada)
                                <tr>
                                    <td><strong>Ubicación:</strong></td>
                                    <td>{{ $asignacion->ubicacion_asignada }}</td>
                                </tr>
                            @endif
                            @if($asignacion->estaActiva())
                                <tr>
                                    <td><strong>Duración:</strong></td>
                                    <td>{{ $asignacion->duracion_en_dias }} días</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

                @if($asignacion->observaciones)
                    <div class="mt-3">
                        <h6 class="text-primary">Observaciones</h6>
                        <div class="alert alert-light">
                            {{ $asignacion->observaciones }}
                        </div>
                    </div>
                @endif

                <!-- Acciones según el estado -->
                <div class="mt-4">
                    @if($asignacion->estaActiva())
                        <!-- Modal para devolver -->
                        <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#devolverModal">
                            <i class="bi bi-arrow-return-left"></i> Devolver Activo
                        </button>

                        <!-- Modal para marcar como perdido -->
                        <button type="button" class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#perdidoModal">
                            <i class="bi bi-exclamation-triangle"></i> Marcar como Perdido
                        </button>
                    @endif

                    <!-- Generar acta de asignación -->
                    <a href="{{ route('asignaciones.acta', $asignacion->id) }}" class="btn btn-primary me-2" target="_blank">
                        <i class="bi bi-file-earmark-pdf"></i> Generar Acta PDF
                    </a>

                    <a href="{{ route('asignaciones.edit', $asignacion) }}" class="btn btn-outline-warning me-2">
                        <i class="bi bi-pencil"></i> Editar
                    </a>

                    <a href="{{ route('asignaciones.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-graph-up"></i> Estadísticas del Activo
                </h6>
            </div>
            <div class="card-body">
                @if($asignacion->activo)
                    @php
                        $estadisticas = $asignacion->activo->estadisticasAsignaciones();
                    @endphp
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <h5 class="text-primary mb-0">{{ $estadisticas['total_asignaciones'] }}</h5>
                                <small>Total Asignaciones</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <h5 class="text-success mb-0">{{ $estadisticas['asignaciones_activas'] }}</h5>
                                <small>Activas</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <h5 class="text-info mb-0">{{ $estadisticas['asignaciones_devueltas'] }}</h5>
                                <small>Devueltas</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <h5 class="text-warning mb-0">{{ $estadisticas['asignaciones_perdidas'] }}</h5>
                                <small>Perdidas</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('asignaciones.historial-activo', $asignacion->activo) }}" class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-clock-history"></i> Ver Historial Completo
                        </a>
                    </div>
                @else
                    <div class="alert alert-warning text-center">
                        <i class="bi bi-exclamation-triangle"></i>
                        No se pueden mostrar estadísticas porque el activo no existe.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal para devolver activo -->
<div class="modal fade" id="devolverModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Devolver Activo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('asignaciones.devolver', $asignacion) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fecha_devolucion" class="form-label">Fecha de Devolución <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_devolucion" id="fecha_devolucion" 
                               class="form-control" value="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="observaciones_devolucion" class="form-label">Observaciones</label>
                        <textarea name="observaciones_devolucion" id="observaciones_devolucion" 
                                  class="form-control" rows="3" 
                                  placeholder="Observaciones sobre la devolución..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Confirmar Devolución</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para marcar como perdido -->
<div class="modal fade" id="perdidoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Marcar como Perdido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('asignaciones.marcar-perdido', $asignacion) }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Advertencia:</strong> Esta acción marcará el activo como perdido. Esta acción no se puede deshacer.
                    </div>
                    <div class="mb-3">
                        <label for="observaciones_perdida" class="form-label">Observaciones <span class="text-danger">*</span></label>
                        <textarea name="observaciones_perdida" id="observaciones_perdida" 
                                  class="form-control" rows="3" 
                                  placeholder="Detalle las circunstancias de la pérdida..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Confirmar Pérdida</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Establecer fecha mínima como fecha de asignación
    document.getElementById('fecha_devolucion').min = '{{ $asignacion->fecha_asignacion->toDateString() }}';
</script>
@endpush
@endsection

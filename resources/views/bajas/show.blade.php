@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-trash"></i> Detalle de Baja de Activo
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('bajas.index') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-arrow-left"></i> Volver a Bajas
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Información principal -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="bi bi-info-circle"></i> Información de la Baja</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Número de Acta:</strong> 
                                                <span class="badge bg-secondary fs-6">{{ $baja->numero_acta }}</span>
                                            </p>
                                            <p><strong>Fecha de Baja:</strong> {{ $baja->fecha_baja->format('d/m/Y') }}</p>
                                            <p><strong>Motivo:</strong> 
                                                <span class="badge bg-{{ $baja->motivo == 'obsoleto' ? 'warning' : ($baja->motivo == 'dañado' ? 'danger' : ($baja->motivo == 'perdido' ? 'info' : ($baja->motivo == 'vendido' ? 'success' : ($baja->motivo == 'donado' ? 'primary' : 'secondary')))) }}">
                                                    {{ $baja->motivo_formateado }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Valor Residual:</strong> 
                                                @if($baja->tieneValorResidual())
                                                    ${{ number_format($baja->valor_residual, 2) }}
                                                @else
                                                    <span class="text-muted">No especificado</span>
                                                @endif
                                            </p>
                                            <p><strong>Destino:</strong> 
                                                {{ $baja->destino ?: 'No especificado' }}
                                            </p>
                                            <p><strong>Procesado por:</strong> {{ $baja->usuario->name }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <p><strong>Descripción del Motivo:</strong></p>
                                        <div class="alert alert-light">
                                            {{ $baja->descripcion_motivo }}
                                        </div>
                                    </div>

                                    @if($baja->observaciones)
                                        <div class="mt-3">
                                            <p><strong>Observaciones:</strong></p>
                                            <div class="alert alert-info">
                                                {{ $baja->observaciones }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="bi bi-box"></i> Información del Activo</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Código:</strong> {{ $baja->activo->codigo }}</p>
                                    <p><strong>Nombre:</strong> {{ $baja->activo->nombre }}</p>
                                    <p><strong>Grupo:</strong> {{ $baja->activo->grupo_articulo ?: 'Sin grupo' }}</p>
                                    <p><strong>Valor Original:</strong> ${{ number_format($baja->activo->valor_compra, 2) }}</p>
                                    <p><strong>Fecha Adquisición:</strong> {{ $baja->activo->fecha_compra ? $baja->activo->fecha_compra->format('d/m/Y') : 'No especificada' }}</p>
                                    <p><strong>Estado Actual:</strong> 
                                        <span class="badge bg-danger">Dado de Baja</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="bi bi-gear"></i> Acciones</h5>
                                </div>
                                <div class="card-body">
                                    <a href="{{ route('bajas.acta', $baja) }}" class="btn btn-success">
                                        <i class="bi bi-file-earmark-pdf"></i> Generar Acta de Baja (PDF)
                                    </a>
                                    <a href="{{ route('activos.show', $baja->activo) }}" class="btn btn-info">
                                        <i class="bi bi-eye"></i> Ver Activo
                                    </a>
                                    <a href="{{ route('bajas.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-list"></i> Ver Todas las Bajas
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

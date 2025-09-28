@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detalles del Activo</h4>
                    <div class="d-flex gap-2">
                        @if(auth()->user()->esAdmin())
                            <a href="{{ route('activos.edit', $activo) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        @endif
                        <a href="{{ route('activos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Información Básica -->
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Información Básica</h5>
                            
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Código:</td>
                                    <td>{{ $activo->codigo }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nombre:</td>
                                    <td>{{ $activo->nombre }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Estado:</td>
                                    <td>
                                        <span class="badge 
                                            @if($activo->estado == 'bueno') bg-success
                                            @elseif($activo->estado == 'regular') bg-warning
                                            @elseif($activo->estado == 'malo') bg-danger
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($activo->estado) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tipo de Bien:</td>
                                    <td>{{ $activo->tipo_bien ?? 'No especificado' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Ubicación:</td>
                                    <td>{{ $activo->ubicacion ?? 'No especificada' }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <!-- Información Técnica -->
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Información Técnica</h5>
                            
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Marca:</td>
                                    <td>{{ $activo->marca ?? 'No especificada' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Modelo:</td>
                                    <td>{{ $activo->modelo ?? 'No especificado' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Serial:</td>
                                    <td>{{ $activo->serial ?? 'No especificado' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Código de Servicio:</td>
                                    <td>{{ $activo->codigo_servicio ?? 'No especificado' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Vida Útil:</td>
                                    <td>{{ $activo->vida_util ?? 'No especificada' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Información Financiera -->
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Información Financiera</h5>
                            
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Valor de Compra:</td>
                                    <td>
                                        @if($activo->valor_compra)
                                            ${{ number_format($activo->valor_compra, 2) }}
                                        @else
                                            No especificado
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Fecha de Compra:</td>
                                    <td>{{ $activo->fecha_compra ? $activo->fecha_compra->format('d/m/Y') : 'No especificada' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Número de Compra:</td>
                                    <td>{{ $activo->nro_compra ?? 'No especificado' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Valor Depreciación:</td>
                                    <td>
                                        @if($activo->valor_depreciacion)
                                            ${{ number_format($activo->valor_depreciacion, 2) }}
                                        @else
                                            No especificado
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Valor Residual:</td>
                                    <td>
                                        @if($activo->valor_residual)
                                            ${{ number_format($activo->valor_residual, 2) }}
                                        @else
                                            No especificado
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <!-- Información de Responsabilidad -->
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Responsabilidad</h5>
                            
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Código Responsable:</td>
                                    <td>{{ $activo->codigo_responsable ?? 'No especificado' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nombre Responsable:</td>
                                    <td>{{ $activo->nombre_responsable ?? 'No especificado' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Código Grupo Artículo:</td>
                                    <td>{{ $activo->codigo_grupo_articulo ?? 'No especificado' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Código Grupo Contable:</td>
                                    <td>{{ $activo->codigo_grupo_contable ?? 'No especificado' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Fecha de Creación:</td>
                                    <td>{{ $activo->created_at ? $activo->created_at->format('d/m/Y H:i') : 'No especificada' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Observaciones -->
                    @if($activo->observacion || $activo->descripcion)
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">Observaciones</h5>
                            
                            @if($activo->observacion)
                            <div class="mb-3">
                                <label class="fw-bold">Observación:</label>
                                <p class="text-muted">{{ $activo->observacion }}</p>
                            </div>
                            @endif
                            
                            @if($activo->descripcion)
                            <div class="mb-3">
                                <label class="fw-bold">Descripción:</label>
                                <p class="text-muted">{{ $activo->descripcion }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <!-- Información Adicional -->
                    @if($activo->recurso || $activo->tipo_adquisicion || $activo->area_administrativa)
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">Información Adicional</h5>
                            
                            <div class="row">
                                @if($activo->recurso)
                                <div class="col-md-4">
                                    <label class="fw-bold">Recurso:</label>
                                    <p class="text-muted">{{ $activo->recurso }}</p>
                                </div>
                                @endif
                                
                                @if($activo->tipo_adquisicion)
                                <div class="col-md-4">
                                    <label class="fw-bold">Tipo de Adquisición:</label>
                                    <p class="text-muted">{{ $activo->tipo_adquisicion }}</p>
                                </div>
                                @endif
                                
                                @if($activo->area_administrativa)
                                <div class="col-md-4">
                                    <label class="fw-bold">Área Administrativa:</label>
                                    <p class="text-muted">{{ $activo->area_administrativa }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Acciones -->
                    <div class="mt-4">
                        <a href="{{ route('activos.edit', $activo) }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        
                        @if($activo->estaDisponible() && !$activo->tieneBaja())
                            <a href="{{ route('bajas.create', $activo) }}" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Dar de Baja
                            </a>
                        @endif
                        
                        <a href="{{ route('activos.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-trash"></i> Dar de Baja Activo
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('activos.show', $activo) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-arrow-left"></i> Volver al Activo
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Información del activo -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h5><i class="bi bi-info-circle"></i> Información del Activo</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Código:</strong> {{ $activo->codigo }}<br>
                                        <strong>Nombre:</strong> {{ $activo->nombre }}<br>
                                        <strong>Grupo:</strong> {{ $activo->grupo_articulo ?: 'Sin grupo' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Valor:</strong> ${{ number_format($activo->valor_compra, 2) }}<br>
                                        <strong>Estado:</strong> 
                                        <span class="badge bg-{{ $activo->estado == 'activo' ? 'success' : ($activo->estado == 'mantenimiento' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($activo->estado) }}
                                        </span><br>
                                        <strong>Fecha Adquisición:</strong> {{ $activo->fecha_compra ? $activo->fecha_compra->format('d/m/Y') : 'No especificada' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de baja -->
                    <form method="POST" action="{{ route('bajas.store', $activo) }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="fecha_baja" class="form-label">Fecha de Baja <span class="text-danger">*</span></label>
                                    <input type="date" name="fecha_baja" id="fecha_baja" class="form-control @error('fecha_baja') is-invalid @enderror" 
                                           value="{{ old('fecha_baja', now()->format('Y-m-d')) }}" required>
                                    @error('fecha_baja')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="motivo" class="form-label">Motivo de Baja <span class="text-danger">*</span></label>
                                    <select name="motivo" id="motivo" class="form-select @error('motivo') is-invalid @enderror" required>
                                        <option value="">Seleccione un motivo</option>
                                        <option value="obsoleto" {{ old('motivo') == 'obsoleto' ? 'selected' : '' }}>Obsoleto</option>
                                        <option value="dañado" {{ old('motivo') == 'dañado' ? 'selected' : '' }}>Dañado</option>
                                        <option value="perdido" {{ old('motivo') == 'perdido' ? 'selected' : '' }}>Perdido</option>
                                        <option value="vendido" {{ old('motivo') == 'vendido' ? 'selected' : '' }}>Vendido</option>
                                        <option value="donado" {{ old('motivo') == 'donado' ? 'selected' : '' }}>Donado</option>
                                        <option value="otro" {{ old('motivo') == 'otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                    @error('motivo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="descripcion_motivo" class="form-label">Descripción del Motivo <span class="text-danger">*</span></label>
                            <textarea name="descripcion_motivo" id="descripcion_motivo" rows="4" 
                                      class="form-control @error('descripcion_motivo') is-invalid @enderror" 
                                      placeholder="Describa detalladamente el motivo de la baja..." required>{{ old('descripcion_motivo') }}</textarea>
                            @error('descripcion_motivo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="valor_residual" class="form-label">Valor Residual</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="valor_residual" id="valor_residual" step="0.01" min="0"
                                               class="form-control @error('valor_residual') is-invalid @enderror" 
                                               value="{{ old('valor_residual') }}" placeholder="0.00">
                                    </div>
                                    <small class="form-text text-muted">Valor estimado del activo al momento de la baja</small>
                                    @error('valor_residual')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="destino" class="form-label">Destino</label>
                                    <input type="text" name="destino" id="destino" 
                                           class="form-control @error('destino') is-invalid @enderror" 
                                           value="{{ old('destino') }}" placeholder="Ej: Basura, Venta, Donación...">
                                    <small class="form-text text-muted">A dónde va el activo después de la baja</small>
                                    @error('destino')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="3" 
                                      class="form-control @error('observaciones') is-invalid @enderror" 
                                      placeholder="Observaciones adicionales...">{{ old('observaciones') }}</textarea>
                            @error('observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Advertencia -->
                        <div class="alert alert-warning">
                            <h6><i class="bi bi-exclamation-triangle"></i> Advertencia</h6>
                            <p class="mb-0">
                                <strong>Esta acción no se puede deshacer.</strong> Una vez dado de baja el activo, 
                                no podrá ser asignado nuevamente y se generará un acta de baja oficial.
                            </p>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Confirmar Baja del Activo
                            </button>
                            <a href="{{ route('activos.show', $activo) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

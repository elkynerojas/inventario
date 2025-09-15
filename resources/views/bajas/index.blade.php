@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-trash"></i> Bajas de Activos
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('activos.index') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-arrow-left"></i> Volver a Activos
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <form method="GET" action="{{ route('bajas.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <label for="motivo" class="form-label">Motivo</label>
                                    <select name="motivo" id="motivo" class="form-select">
                                        <option value="">Todos los motivos</option>
                                        <option value="obsoleto" {{ request('motivo') == 'obsoleto' ? 'selected' : '' }}>Obsoleto</option>
                                        <option value="dañado" {{ request('motivo') == 'dañado' ? 'selected' : '' }}>Dañado</option>
                                        <option value="perdido" {{ request('motivo') == 'perdido' ? 'selected' : '' }}>Perdido</option>
                                        <option value="vendido" {{ request('motivo') == 'vendido' ? 'selected' : '' }}>Vendido</option>
                                        <option value="donado" {{ request('motivo') == 'donado' ? 'selected' : '' }}>Donado</option>
                                        <option value="otro" {{ request('motivo') == 'otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="fecha_desde" class="form-label">Fecha Desde</label>
                                    <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                                    <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="buscar_activo" class="form-label">Buscar Activo</label>
                                    <div class="input-group">
                                        <input type="text" name="buscar_activo" id="buscar_activo" class="form-control" 
                                               placeholder="Código o nombre" value="{{ request('buscar_activo') }}">
                                        <button type="button" class="btn btn-outline-secondary" onclick="limpiarActivo()">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">Buscar por código o nombre del activo</small>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search"></i> Filtrar
                                    </button>
                                    <a href="{{ route('bajas.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-clockwise"></i> Limpiar Filtros
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Estadísticas -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-danger"><i class="bi bi-trash"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Bajas</span>
                                            <span class="info-box-number">{{ $estadisticas['total'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning"><i class="bi bi-clock-history"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Obsoletos</span>
                                            <span class="info-box-number">{{ $estadisticas['obsoleto'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-danger"><i class="bi bi-exclamation-triangle"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Dañados</span>
                                            <span class="info-box-number">{{ $estadisticas['dañado'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="bi bi-question-circle"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Perdidos</span>
                                            <span class="info-box-number">{{ $estadisticas['perdido'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success"><i class="bi bi-currency-dollar"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Vendidos</span>
                                            <span class="info-box-number">{{ $estadisticas['vendido'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-primary"><i class="bi bi-gift"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Donados</span>
                                            <span class="info-box-number">{{ $estadisticas['donado'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de bajas -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>N° Acta</th>
                                    <th>Activo</th>
                                    <th>Motivo</th>
                                    <th>Fecha Baja</th>
                                    <th>Valor Residual</th>
                                    <th>Procesado por</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bajas as $baja)
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">{{ $baja->numero_acta }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $baja->activo->codigo }}</strong><br>
                                                <small class="text-muted">{{ $baja->activo->nombre }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $baja->motivo == 'obsoleto' ? 'warning' : ($baja->motivo == 'dañado' ? 'danger' : ($baja->motivo == 'perdido' ? 'info' : ($baja->motivo == 'vendido' ? 'success' : ($baja->motivo == 'donado' ? 'primary' : 'secondary')))) }}">
                                                {{ $baja->motivo_formateado }}
                                            </span>
                                        </td>
                                        <td>{{ $baja->fecha_baja->format('d/m/Y') }}</td>
                                        <td>
                                            @if($baja->tieneValorResidual())
                                                ${{ number_format($baja->valor_residual, 2) }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $baja->usuario->name }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('bajas.show', $baja) }}" class="btn btn-sm btn-info" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('bajas.acta', $baja) }}" class="btn btn-sm btn-success" title="Generar acta PDF">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox display-4"></i>
                                                <p class="mt-2">No hay bajas registradas</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center">
                        {{ $bajas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function limpiarActivo() {
    document.getElementById('buscar_activo').value = '';
}
</script>
@endsection

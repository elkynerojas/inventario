@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">
                    <i class="bi bi-graph-up"></i> Reportes de Activos
                </h2>
            </div>
            <div class="card-body">
                <!-- Filtros de búsqueda -->
                <div class="row mb-4">
                    <div class="col-12">
                        <form method="GET" action="{{ route('reportes.index') }}" class="row g-3">
                            <div class="col-md-3">
                                <select name="filtro" class="form-select">
                                    <option value="">Todos los activos</option>
                                    <option value="estado" {{ request('filtro') === 'estado' ? 'selected' : '' }}>Por Estado</option>
                                    <option value="ubicacion" {{ request('filtro') === 'ubicacion' ? 'selected' : '' }}>Por Ubicación</option>
                                    <option value="responsable" {{ request('filtro') === 'responsable' ? 'selected' : '' }}>Por Responsable</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="parametro" class="form-control" 
                                       placeholder="Parámetro de búsqueda" value="{{ request('parametro') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search"></i> Buscar
                                </button>
                            </div>
                            <div class="col-md-3">
                                <div class="btn-group w-100" role="group">
                                    <a href="{{ route('reportes.excel', request()->query()) }}" class="btn btn-success">
                                        <i class="bi bi-file-excel"></i> Excel
                                    </a>
                                    <a href="{{ route('reportes.pdf', request()->query()) }}" class="btn btn-danger">
                                        <i class="bi bi-file-pdf"></i> PDF
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h3>{{ $estadisticas['total'] }}</h3>
                                <p class="mb-0">Total Activos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h3>{{ $estadisticas['bueno'] }}</h3>
                                <p class="mb-0">En Buen Estado</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark">
                            <div class="card-body text-center">
                                <h3>{{ $estadisticas['regular'] }}</h3>
                                <p class="mb-0">Estado Regular</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <h3>{{ $estadisticas['malo'] }}</h3>
                                <p class="mb-0">En Mal Estado</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de resultados -->
                @if($activos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Serial</th>
                                    <th>Estado</th>
                                    <th>Ubicación</th>
                                    <th>Responsable</th>
                                    <th>Valor</th>
                                    <th>Fecha Compra</th>
                                    <th>Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activos as $activo)
                                <tr>
                                    <td>{{ $activo->id }}</td>
                                    <td>{{ $activo->codigo }}</td>
                                    <td>{{ $activo->nombre }}</td>
                                    <td>{{ $activo->marca ?: 'No especificada' }}</td>
                                    <td>{{ $activo->modelo ?: 'No especificado' }}</td>
                                    <td>{{ $activo->serial ?: 'No especificado' }}</td>
                                    <td>
                                        <span class="badge badge-estado {{ strtolower(str_replace(' ', '-', $activo->estado)) }}">
                                            {{ $activo->estado }}
                                        </span>
                                    </td>
                                    <td>{{ $activo->ubicacion }}</td>
                                    <td>{{ $activo->nombre_responsable }}</td>
                                    <td>${{ number_format($activo->valor_compra, 2) }}</td>
                                    <td>{{ $activo->fecha_compra ? $activo->fecha_compra->format('d/m/Y') : 'N/A' }}</td>
                                    <td>{{ $activo->tipo_bien }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-graph-up" style="font-size: 3rem; color: #6c757d;"></i>
                        <h3 class="mt-3">No hay activos que coincidan con los filtros</h3>
                        <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Inventario de Activos')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Header de la página -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="bi bi-box-seam"></i> Inventario de Activos</h2>
                    <div class="d-flex gap-2">
                        <a href="{{ route('reportes.index') }}" class="btn btn-info">
                            <i class="bi bi-file-earmark-text"></i> Reportes
                        </a>
                        @if(auth()->user()->esAdmin())
                        <a href="{{ route('activos.create') }}" class="btn btn-danger">
                            <i class="bi bi-plus-lg"></i> Nuevo Activo
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Barra de búsqueda -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('activos.index') }}">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="buscar" class="form-control" 
                               placeholder="Buscar por código, nombre, ubicación, responsable..." 
                               value="{{ request('buscar') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de activos -->
        <div class="card">
            <div class="card-body p-0">
                @if($activos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Estado</th>
                                    <th>Ubicación</th>
                                    <th>Responsable</th>
                                    <th>Valor</th>
                                    <th>Fecha Compra</th>
                                    @if(auth()->user()->esAdmin())
                                    <th>Acciones</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activos as $activo)
                                <tr>
                                    <td>{{ $activo->id }}</td>
                                    <td>{{ $activo->codigo }}</td>
                                    <td>{{ $activo->nombre }}</td>
                                    <td>
                                        <span class="badge badge-estado {{ strtolower(str_replace(' ', '-', $activo->estado)) }}">
                                            {{ $activo->estado }}
                                        </span>
                                    </td>
                                    <td>{{ $activo->ubicacion }}</td>
                                    <td>{{ $activo->nombre_responsable }}</td>
                                    <td>${{ number_format($activo->valor_compra, 2) }}</td>
                                    <td>{{ $activo->fecha_compra ? $activo->fecha_compra->format('d/m/Y') : 'N/A' }}</td>
                                    @if(auth()->user()->esAdmin())
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('activos.show', $activo) }}" class="btn btn-outline-primary btn-sm" title="Ver detalles">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('activos.edit', $activo) }}" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('activos.destroy', $activo) }}" method="POST" style="display:inline;" 
                                                  onsubmit="return confirm('¿Estás seguro de eliminar este activo?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación -->
                    <div class="card-footer">
                        {{ $activos->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-box-seam" style="font-size: 3rem; color: #6c757d;"></i>
                        <h3 class="mt-3">No hay activos registrados</h3>
                        <p class="text-muted">Comienza agregando el primer activo al inventario</p>
                        @if(auth()->user()->esAdmin())
                        <a href="{{ route('activos.create') }}" class="btn btn-danger mt-3">
                            <i class="bi bi-plus-lg"></i> Agregar Primer Activo
                        </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

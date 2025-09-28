@extends('layouts.app')

@section('title', 'Asignaciones de Activos')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-person-check"></i> Asignaciones de Activos
                </h5>
                @if(auth()->user()->esAdmin())
                <div class="d-flex gap-2">
                    <a href="{{ route('actas.index') }}" class="btn btn-success">
                        <i class="bi bi-file-earmark-text"></i> Generar Actas
                    </a>
                    <a href="{{ route('asignaciones.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Nueva Asignación
                    </a>
                </div>
                @endif
            </div>
            <div class="card-body">
                <!-- Estadísticas -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h4>{{ $estadisticas['total'] }}</h4>
                                <small>Total Asignaciones</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h4>{{ $estadisticas['activas'] }}</h4>
                                <small>Activas</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h4>{{ $estadisticas['devueltas'] }}</h4>
                                <small>Devueltas</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h4>{{ $estadisticas['perdidas'] }}</h4>
                                <small>Perdidas</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtros -->
                @if(auth()->user()->esAdmin())
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select name="estado" id="estado" class="form-select">
                            <option value="">Todos los estados</option>
                            <option value="activa" {{ request('estado') == 'activa' ? 'selected' : '' }}>Activa</option>
                            <option value="devuelta" {{ request('estado') == 'devuelta' ? 'selected' : '' }}>Devuelta</option>
                            <option value="perdida" {{ request('estado') == 'perdida' ? 'selected' : '' }}>Perdida</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="documento_usuario" class="form-label">Documento Usuario</label>
                        <div class="input-group">
                            <input type="text" name="documento_usuario" id="documento_usuario" 
                                   class="form-control" value="{{ request('documento_usuario') }}" 
                                   placeholder="Ej: 12345678"
                                   autocomplete="off">
                            <button class="btn btn-outline-secondary" type="button" id="limpiarDocumento" title="Limpiar búsqueda">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </div>
                        <small class="text-muted">Busca por número de documento</small>
                    </div>
                    <div class="col-md-3">
                        <label for="buscar_activo" class="form-label">Buscar Activo</label>
                        <div class="input-group">
                            <input type="text" name="buscar_activo" id="buscar_activo" 
                                   class="form-control" value="{{ request('buscar_activo') }}" 
                                   placeholder="Código o nombre del activo"
                                   autocomplete="off">
                            <button class="btn btn-outline-secondary" type="button" id="limpiarActivo" title="Limpiar búsqueda">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </div>
                        <small class="text-muted">Busca por código (ej: ACT001) o nombre (ej: Laptop)</small>
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_desde" class="form-label">Fecha Desde</label>
                        <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                        <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                        <a href="{{ route('asignaciones.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Limpiar
                        </a>
                    </div>
                </form>
                @endif

                <!-- Tabla de asignaciones -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Activo</th>
                                <th>Usuario Asignado</th>
                                <th>Fecha Asignación</th>
                                <th>Estado</th>
                                <th>Asignado Por</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($asignaciones as $asignacion)
                                <tr>
                                    <td>{{ $asignacion->id }}</td>
                                    <td>
                                        <strong>{{ $asignacion->activo->codigo }}</strong><br>
                                        <small class="text-muted">{{ $asignacion->activo->nombre }}</small>
                                    </td>
                                    <td>{{ $asignacion->usuario->name }}</td>
                                    <td>{{ $asignacion->fecha_asignacion->format('d/m/Y') }}</td>
                                    <td>
                                        @if($asignacion->estado == 'activa')
                                            <span class="badge bg-success">Activa</span>
                                        @elseif($asignacion->estado == 'devuelta')
                                            <span class="badge bg-info">Devuelta</span>
                                        @elseif($asignacion->estado == 'perdida')
                                            <span class="badge bg-warning">Perdida</span>
                                        @endif
                                    </td>
                                    <td>{{ $asignacion->asignadoPor->name }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('asignaciones.show', ['asignacione' => $asignacion->id]) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($asignacion->estaActiva())
                                                <a href="{{ route('asignaciones.edit', ['asignacione' => $asignacion->id]) }}" class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif
                                            @if(!$asignacion->estaActiva())
                                                <form method="POST" action="{{ route('asignaciones.destroy', $asignacion) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Está seguro de eliminar esta asignación?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                        No se encontraron asignaciones
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if($asignaciones->hasPages())
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="pagination-info">
                                Mostrando {{ $asignaciones->firstItem() }} a {{ $asignaciones->lastItem() }} de {{ $asignaciones->total() }} resultados
                            </div>
                            <nav>
                                {{ $asignaciones->appends(request()->query())->links() }}
                            </nav>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Limpiar campos de búsqueda
    document.getElementById('limpiarDocumento').addEventListener('click', function() {
        document.getElementById('documento_usuario').value = '';
    });
    
    document.getElementById('limpiarActivo').addEventListener('click', function() {
        document.getElementById('buscar_activo').value = '';
    });
    
    // Limpiar todos los filtros
    document.querySelector('a[href="{{ route('asignaciones.index') }}"]').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('estado').value = '';
        document.getElementById('documento_usuario').value = '';
        document.getElementById('buscar_activo').value = '';
        document.getElementById('fecha_desde').value = '';
        document.getElementById('fecha_hasta').value = '';
        window.location.href = '{{ route('asignaciones.index') }}';
    });
</script>
@endpush
@endsection

@extends('layouts.app')

@section('title', 'Configuraciones del Sistema')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Header de la página -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="bi bi-gear"></i> Configuraciones del Sistema</h2>
                    <div class="d-flex gap-2">
                        <a href="{{ route('configuraciones.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Nueva Configuración
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-download"></i> Exportar/Importar
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('configuraciones.exportar') }}">
                                    <i class="bi bi-download"></i> Exportar JSON
                                </a></li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#importarModal">
                                    <i class="bi bi-upload"></i> Importar JSON
                                </a></li>
                            </ul>
                        </div>
                        <form method="POST" action="{{ route('configuraciones.restaurar-defecto') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning" 
                                    onclick="return confirm('¿Está seguro de restaurar las configuraciones por defecto?')">
                                <i class="bi bi-arrow-clockwise"></i> Restaurar Defecto
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros por categoría -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-funnel"></i> Filtrar por Categoría</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($categorias as $cat)
                    <div class="col-md-2 mb-2">
                        <a href="{{ route('configuraciones.index', ['categoria' => $cat]) }}" 
                           class="btn {{ $categoria === $cat ? 'btn-primary' : 'btn-outline-primary' }} w-100">
                            {{ ucfirst($cat) }}
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Tabla de configuraciones -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Configuraciones - {{ ucfirst($categoria) }}</h5>
                    <span class="badge bg-primary">{{ $configuraciones->total() }} configuración{{ $configuraciones->total() != 1 ? 'es' : '' }}</span>
                </div>
            </div>
            <div class="card-body">
                @if($configuraciones->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Clave</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Valor</th>
                                    <th>Estado</th>
                                    <th>Requerido</th>
                                    <th>Orden</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($configuraciones as $config)
                                <tr>
                                    <td>
                                        <code>{{ $config->clave }}</code>
                                    </td>
                                    <td>
                                        <strong>{{ $config->nombre }}</strong>
                                        @if($config->descripcion)
                                            <br><small class="text-muted">{{ Str::limit($config->descripcion, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($config->tipo) }}</span>
                                    </td>
                                    <td>
                                        @if($config->tipo === 'file' && $config->valor)
                                            @php
                                                $urlArchivo = \App\Http\Controllers\ConfiguracionController::obtenerUrlArchivo($config->valor);
                                            @endphp
                                            @if($urlArchivo)
                                                <a href="{{ $urlArchivo }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-file-earmark"></i> Ver archivo
                                                </a>
                                            @else
                                                <span class="text-muted">Archivo no encontrado</span>
                                            @endif
                                        @elseif($config->tipo === 'boolean')
                                            <span class="badge {{ $config->valor === 'true' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $config->valor === 'true' ? 'Sí' : 'No' }}
                                            </span>
                                        @elseif($config->tipo === 'json')
                                            <code>{{ Str::limit($config->valor, 30) }}</code>
                                        @else
                                            {{ Str::limit($config->valor, 30) }}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $config->activo ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $config->activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($config->requerido)
                                            <span class="badge bg-warning">
                                                <i class="bi bi-exclamation-triangle"></i> Requerido
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $config->orden }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('configuraciones.show', $config) }}" 
                                               class="btn btn-sm btn-outline-info" title="Ver detalles">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('configuraciones.edit', $config) }}" 
                                               class="btn btn-sm btn-outline-primary" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if(!$config->requerido)
                                            <form method="POST" action="{{ route('configuraciones.destroy', $config) }}" 
                                                  class="d-inline" 
                                                  onsubmit="return confirm('¿Está seguro de eliminar esta configuración?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center">
                        {{ $configuraciones->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-gear display-1 text-muted"></i>
                        <h4 class="text-muted mt-3">No hay configuraciones</h4>
                        <p class="text-muted">No se encontraron configuraciones en la categoría "{{ ucfirst($categoria) }}".</p>
                        <a href="{{ route('configuraciones.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Crear Primera Configuración
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal para importar configuraciones -->
<div class="modal fade" id="importarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Importar Configuraciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('configuraciones.importar') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="archivo" class="form-label">Archivo JSON</label>
                        <input type="file" class="form-control" id="archivo" name="archivo" accept=".json" required>
                        <div class="form-text">Seleccione un archivo JSON con las configuraciones a importar.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Importar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

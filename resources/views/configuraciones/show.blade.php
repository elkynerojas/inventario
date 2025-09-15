@extends('layouts.app')

@section('title', 'Detalles de Configuración')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Header de la página -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="bi bi-eye"></i> Detalles de Configuración</h2>
                    <div class="d-flex gap-2">
                        <a href="{{ route('configuraciones.edit', $configuracion) }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="{{ route('configuraciones.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información principal -->
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Información General</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Clave:</strong><br>
                                <code class="fs-6">{{ $configuracion->clave }}</code>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Categoría:</strong><br>
                                <span class="badge bg-primary">{{ ucfirst($configuracion->categoria) }}</span>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Nombre:</strong><br>
                                {{ $configuracion->nombre }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Tipo:</strong><br>
                                <span class="badge bg-info">{{ ucfirst($configuracion->tipo) }}</span>
                            </div>
                        </div>

                        @if($configuracion->descripcion)
                        <div class="mb-3">
                            <strong>Descripción:</strong><br>
                            <p class="text-muted">{{ $configuracion->descripcion }}</p>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <strong>Estado:</strong><br>
                                <span class="badge {{ $configuracion->activo ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $configuracion->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Requerido:</strong><br>
                                @if($configuracion->requerido)
                                    <span class="badge bg-warning">
                                        <i class="bi bi-exclamation-triangle"></i> Sí
                                    </span>
                                @else
                                    <span class="text-muted">No</span>
                                @endif
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Orden:</strong><br>
                                {{ $configuracion->orden }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Valor de la configuración -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Valor de la Configuración</h5>
                    </div>
                    <div class="card-body">
                        @if($configuracion->tipo === 'file' && $configuracion->valor)
                            @php
                                $urlArchivo = \App\Http\Controllers\ConfiguracionController::obtenerUrlArchivo($configuracion->valor);
                            @endphp
                            <div class="text-center">
                                <i class="bi bi-file-earmark display-1 text-primary"></i>
                                <h5 class="mt-3">Archivo</h5>
                                <p class="text-muted">{{ basename($configuracion->valor) }}</p>
                                @if($urlArchivo)
                                    <a href="{{ $urlArchivo }}" target="_blank" class="btn btn-primary">
                                        <i class="bi bi-download"></i> Descargar Archivo
                                    </a>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="bi bi-exclamation-triangle"></i> Archivo no encontrado
                                    </div>
                                @endif
                            </div>
                        @elseif($configuracion->tipo === 'boolean')
                            <div class="text-center">
                                <i class="bi bi-{{ $configuracion->valor === 'true' ? 'check-circle-fill text-success' : 'x-circle-fill text-danger' }} display-1"></i>
                                <h5 class="mt-3">{{ $configuracion->valor === 'true' ? 'Activado' : 'Desactivado' }}</h5>
                            </div>
                        @elseif($configuracion->tipo === 'json')
                            <div class="bg-light p-3 rounded">
                                <pre class="mb-0"><code>{{ json_encode(json_decode($configuracion->valor), JSON_PRETTY_PRINT) }}</code></pre>
                            </div>
                        @elseif($configuracion->tipo === 'number' || $configuracion->tipo === 'integer' || $configuracion->tipo === 'float')
                            <div class="text-center">
                                <h2 class="text-primary">{{ number_format($configuracion->valor, $configuracion->tipo === 'float' ? 2 : 0) }}</h2>
                            </div>
                        @else
                            <div class="bg-light p-3 rounded">
                                <p class="mb-0 fs-5">{{ $configuracion->valor ?: 'Sin valor' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Opciones (si existen) -->
                @if($configuracion->opciones && count($configuracion->opciones) > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Opciones Disponibles</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($configuracion->opciones as $key => $value)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>{{ $key }}:</strong></span>
                                <span class="text-muted">{{ $value }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Información del sistema -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Información del Sistema</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>ID:</strong><br>
                            <code>{{ $configuracion->id }}</code>
                        </div>
                        <div class="mb-3">
                            <strong>Creado:</strong><br>
                            <small class="text-muted">{{ $configuracion->created_at ? $configuracion->created_at->format('d/m/Y H:i:s') : 'No disponible' }}</small>
                        </div>
                        <div class="mb-3">
                            <strong>Actualizado:</strong><br>
                            <small class="text-muted">{{ $configuracion->updated_at ? $configuracion->updated_at->format('d/m/Y H:i:s') : 'No disponible' }}</small>
                        </div>
                        <div class="mb-3">
                            <strong>Validez:</strong><br>
                            @if($configuracion->esValida())
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Válida
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="bi bi-exclamation-triangle"></i> Inválida
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Acciones rápidas -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Acciones Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('configuraciones.edit', $configuracion) }}" class="btn btn-primary">
                                <i class="bi bi-pencil"></i> Editar Configuración
                            </a>
                            @if(!$configuracion->requerido)
                            <form method="POST" action="{{ route('configuraciones.destroy', $configuracion) }}" 
                                  onsubmit="return confirm('¿Está seguro de eliminar esta configuración?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('configuraciones.index', ['categoria' => $configuracion->categoria]) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-list"></i> Ver Categoría
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

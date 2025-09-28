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
                <!-- Filtros y Búsqueda -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-funnel"></i> Filtros para Reportes</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('reportes.index') }}" id="filtrosReportesForm">
                            <div class="row g-3">
                                <!-- Búsqueda general -->
                                <div class="col-md-4">
                                    <label for="buscar" class="form-label">Búsqueda General</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-search"></i>
                                        </span>
                                        <input type="text" name="buscar" id="buscar" class="form-control" 
                                               placeholder="Código, nombre, ubicación, responsable..." 
                                               value="{{ request('buscar') }}">
                                    </div>
                                </div>

                                <!-- Filtro por Estado -->
                                <div class="col-md-2">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select name="estado" id="estado" class="form-select">
                                        <option value="">Todos los estados</option>
                                        <option value="bueno" {{ request('estado') == 'bueno' ? 'selected' : '' }}>Bueno</option>
                                        <option value="regular" {{ request('estado') == 'regular' ? 'selected' : '' }}>Regular</option>
                                        <option value="malo" {{ request('estado') == 'malo' ? 'selected' : '' }}>Malo</option>
                                        <option value="dado de baja" {{ request('estado') == 'dado de baja' ? 'selected' : '' }}>Dado de Baja</option>
                                    </select>
                                </div>

                                <!-- Filtro por Ubicación -->
                                <div class="col-md-2">
                                    <label for="ubicacion" class="form-label">Ubicación</label>
                                    <select name="ubicacion" id="ubicacion" class="form-select">
                                        <option value="">Todas las ubicaciones</option>
                                        @foreach($ubicaciones ?? [] as $ubicacion)
                                        <option value="{{ $ubicacion }}" {{ request('ubicacion') == $ubicacion ? 'selected' : '' }}>
                                            {{ $ubicacion }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filtro por Responsable -->
                                <div class="col-md-2">
                                    <label for="responsable" class="form-label">Responsable</label>
                                    <select name="responsable" id="responsable" class="form-select">
                                        <option value="">Todos los responsables</option>
                                        @foreach($responsables ?? [] as $responsable)
                                        <option value="{{ $responsable }}" {{ request('responsable') == $responsable ? 'selected' : '' }}>
                                            {{ $responsable }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Filtro por Rango de Valor -->
                                <div class="col-md-2">
                                    <label for="valor_minimo" class="form-label">Valor Mínimo</label>
                                    <input type="number" name="valor_minimo" id="valor_minimo" class="form-control" 
                                           placeholder="0" value="{{ request('valor_minimo') }}" step="0.01" min="0">
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <!-- Filtro por Rango de Valor Máximo -->
                                <div class="col-md-2">
                                    <label for="valor_maximo" class="form-label">Valor Máximo</label>
                                    <input type="number" name="valor_maximo" id="valor_maximo" class="form-control" 
                                           placeholder="999999" value="{{ request('valor_maximo') }}" step="0.01" min="0">
                                </div>

                                <!-- Filtro por Fecha de Compra -->
                                <div class="col-md-2">
                                    <label for="fecha_desde" class="form-label">Fecha Desde</label>
                                    <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" 
                                           value="{{ request('fecha_desde') }}">
                                </div>

                                <div class="col-md-2">
                                    <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                                    <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" 
                                           value="{{ request('fecha_hasta') }}">
                                </div>

                                <!-- Botones de acción -->
                                <div class="col-md-6 d-flex align-items-end gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search"></i> Filtrar
                                    </button>
                                    <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-clockwise"></i> Limpiar
                                    </a>
                                    <a href="{{ route('reportes.excel', request()->query()) }}" class="btn btn-success">
                                        <i class="bi bi-file-excel"></i> Exportar Excel
                                    </a>
                                    <a href="{{ route('reportes.pdf', request()->query()) }}" class="btn btn-danger">
                                        <i class="bi bi-file-pdf"></i> Exportar PDF
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Contador de resultados y filtros activos -->
                @if(request()->hasAny(['buscar', 'estado', 'ubicacion', 'responsable', 'valor_minimo', 'valor_maximo', 'fecha_desde', 'fecha_hasta']))
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="resultados-count">
                                    <i class="bi bi-funnel"></i> 
                                    {{ $activos->total() }} resultado{{ $activos->total() != 1 ? 's' : '' }} encontrado{{ $activos->total() != 1 ? 's' : '' }}
                                </span>
                            </div>
                            <div>
                                <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-x-circle"></i> Limpiar Filtros
                                </a>
                            </div>
                        </div>
                        
                        <!-- Indicadores de filtros activos -->
                        <div class="mt-2">
                            @if(request('buscar'))
                            <span class="badge bg-primary me-1 mb-1">
                                <i class="bi bi-search"></i> Búsqueda: "{{ request('buscar') }}"
                            </span>
                            @endif
                            
                            @if(request('estado'))
                            <span class="badge bg-success me-1 mb-1">
                                <i class="bi bi-check-circle"></i> Estado: {{ ucfirst(request('estado')) }}
                            </span>
                            @endif
                            
                            @if(request('ubicacion'))
                            <span class="badge bg-info me-1 mb-1">
                                <i class="bi bi-geo-alt"></i> Ubicación: {{ request('ubicacion') }}
                            </span>
                            @endif
                            
                            @if(request('responsable'))
                            <span class="badge bg-warning me-1 mb-1">
                                <i class="bi bi-person"></i> Responsable: {{ request('responsable') }}
                            </span>
                            @endif
                            
                            @if(request('valor_minimo') || request('valor_maximo'))
                            <span class="badge bg-secondary me-1 mb-1">
                                <i class="bi bi-currency-dollar"></i> Valor: 
                                @if(request('valor_minimo') && request('valor_maximo'))
                                    ${{ number_format(request('valor_minimo'), 2) }} - ${{ number_format(request('valor_maximo'), 2) }}
                                @elseif(request('valor_minimo'))
                                    Desde ${{ number_format(request('valor_minimo'), 2) }}
                                @else
                                    Hasta ${{ number_format(request('valor_maximo'), 2) }}
                                @endif
                            </span>
                            @endif
                            
                            @if(request('fecha_desde') || request('fecha_hasta'))
                            <span class="badge bg-dark me-1 mb-1">
                                <i class="bi bi-calendar"></i> Fecha: 
                                @if(request('fecha_desde') && request('fecha_hasta'))
                                    {{ \Carbon\Carbon::parse(request('fecha_desde'))->format('d/m/Y') }} - {{ \Carbon\Carbon::parse(request('fecha_hasta'))->format('d/m/Y') }}
                                @elseif(request('fecha_desde'))
                                    Desde {{ \Carbon\Carbon::parse(request('fecha_desde'))->format('d/m/Y') }}
                                @else
                                    Hasta {{ \Carbon\Carbon::parse(request('fecha_hasta'))->format('d/m/Y') }}
                                @endif
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

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

<script>
// Auto-submit cuando cambian los selects
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('select[name="estado"], select[name="ubicacion"], select[name="responsable"]');
    
    selects.forEach(select => {
        select.addEventListener('change', function() {
            // Solo auto-submit si hay un valor seleccionado
            if (this.value !== '') {
                document.getElementById('filtrosReportesForm').submit();
            }
        });
    });
    
    // Validación de rangos de valor
    const valorMinimo = document.getElementById('valor_minimo');
    const valorMaximo = document.getElementById('valor_maximo');
    
    function validarRangoValor() {
        if (valorMinimo.value && valorMaximo.value) {
            if (parseFloat(valorMinimo.value) > parseFloat(valorMaximo.value)) {
                valorMaximo.setCustomValidity('El valor máximo debe ser mayor al mínimo');
            } else {
                valorMaximo.setCustomValidity('');
            }
        }
    }
    
    valorMinimo.addEventListener('input', validarRangoValor);
    valorMaximo.addEventListener('input', validarRangoValor);
    
    // Validación de fechas
    const fechaDesde = document.getElementById('fecha_desde');
    const fechaHasta = document.getElementById('fecha_hasta');
    
    function validarRangoFechas() {
        if (fechaDesde.value && fechaHasta.value) {
            if (new Date(fechaDesde.value) > new Date(fechaHasta.value)) {
                fechaHasta.setCustomValidity('La fecha hasta debe ser posterior a la fecha desde');
            } else {
                fechaHasta.setCustomValidity('');
            }
        }
    }
    
    fechaDesde.addEventListener('change', validarRangoFechas);
    fechaHasta.addEventListener('change', validarRangoFechas);
});

// Función para limpiar filtros específicos
function limpiarFiltro(filtro) {
    const elemento = document.getElementById(filtro);
    if (elemento) {
        elemento.value = '';
        elemento.dispatchEvent(new Event('change'));
    }
}

// Función para exportar con filtros aplicados
function exportarConFiltros(tipo) {
    const form = document.getElementById('filtrosReportesForm');
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    // Agregar solo los parámetros que tienen valor
    for (let [key, value] of formData.entries()) {
        if (value.trim() !== '') {
            params.append(key, value);
        }
    }
    
    // Crear URL con parámetros
    const exportUrl = tipo === 'excel' 
        ? '{{ route("reportes.excel") }}?' + params.toString()
        : '{{ route("reportes.pdf") }}?' + params.toString();
    
    // Abrir en nueva ventana para exportar
    window.open(exportUrl, '_blank');
}
</script>
@endsection

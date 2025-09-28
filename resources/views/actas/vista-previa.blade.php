@extends('layouts.app')

@section('title', 'Vista Previa - Acta de Asignación')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-eye me-2"></i>Vista Previa - Acta de Asignación
                </h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('actas.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Volver
                    </a>
                    <button type="button" class="btn btn-success" onclick="generarPDF()">
                        <i class="bi bi-file-earmark-pdf me-2"></i>Generar PDF
                    </button>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <!-- Encabezado del Acta -->
                    <div class="d-flex align-items-center justify-content-center mb-4 border-bottom pb-3">
                        <div class="me-4">
                            <img src="{{ $colegio_logo }}" alt="Logo del Colegio" class="img-fluid" style="max-width: 80px; max-height: 80px;">
                        </div>
                        <div class="text-center">
                            <h2 class="fw-bold mb-1">{{ $colegio_nombre }}</h2>
                            <h3 class="fw-bold text-primary mb-1">ACTA DE ASIGNACIÓN DE ACTIVOS</h3>
                            <p class="text-muted mb-0">{{ $colegio_direccion }}</p>
                        </div>
                    </div>

                    <!-- Información del Usuario -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="fw-bold">Información del Usuario:</h5>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="fw-bold">Nombre:</td>
                                    <td>{{ $usuario->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Documento:</td>
                                    <td>{{ $usuario->documento }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Email:</td>
                                    <td>{{ $usuario->email }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="fw-bold">Información del Acta:</h5>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="fw-bold">Fecha:</td>
                                    <td>{{ \Carbon\Carbon::parse($fecha_acta)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Total Activos:</td>
                                    <td>{{ $total_activos }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Valor Total:</td>
                                    <td>${{ number_format($valor_total, 2, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Lista de Activos Asignados -->
                    <div class="mb-4">
                        <h5 class="fw-bold">Activos Asignados:</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Serial</th>
                                        <th>Estado</th>
                                        <th>Valor</th>
                                        <th>Fecha Asignación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($asignaciones as $asignacion)
                                    <tr>
                                        <td class="fw-bold">{{ $asignacion->activo->codigo }}</td>
                                        <td>{{ $asignacion->activo->nombre }}</td>
                                        <td>{{ $asignacion->activo->marca ?? 'N/A' }}</td>
                                        <td>{{ $asignacion->activo->modelo ?? 'N/A' }}</td>
                                        <td>{{ $asignacion->activo->serial ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $asignacion->activo->estado === 'bueno' ? 'success' : ($asignacion->activo->estado === 'regular' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($asignacion->activo->estado) }}
                                            </span>
                                        </td>
                                        <td>${{ number_format($asignacion->activo->valor_compra ?? 0, 2, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($asignacion->fecha_asignacion)->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="6" class="fw-bold text-end">Total:</td>
                                        <td class="fw-bold">${{ number_format($valor_total, 2, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="mb-4">
                        <h5 class="fw-bold">Observaciones:</h5>
                        <div class="border p-3 bg-light">
                            <p class="mb-2">El usuario <strong>{{ $usuario->name }}</strong> se compromete a:</p>
                            <ul class="mb-0">
                                <li>Mantener los activos en buen estado</li>
                                <li>Reportar cualquier daño o pérdida inmediatamente</li>
                                <li>Devolver los activos cuando sea requerido</li>
                                <li>Cumplir con las políticas de uso establecidas</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Firmas -->
                    <div class="row mt-5">
                        <div class="col-md-6 text-center">
                            <div class="border-top pt-3">
                                <p class="fw-bold mb-1">{{ $usuario->name }}</p>
                                <p class="text-muted small">Usuario Asignado</p>
                                <p class="text-muted small">C.C. {{ $usuario->documento }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="border-top pt-3">
                                <p class="fw-bold mb-1">{{ $firmado_por }}</p>
                                <p class="text-muted small">{{ $cargo_firmante }}</p>
                                <p class="text-muted small">Fecha: {{ \Carbon\Carbon::parse($fecha_acta)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function generarPDF() {
    const params = new URLSearchParams({
        user_id: '{{ $usuario->id }}',
        fecha_acta: '{{ $fecha_acta }}',
        firmado_por: '{{ $firmado_por }}',
        cargo_firmante: '{{ $cargo_firmante }}'
    });
    
    window.open(`{{ route('actas.generar') }}?${params.toString()}`, '_blank');
}
</script>
@endsection

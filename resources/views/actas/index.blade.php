@extends('layouts.app')

@section('title', 'Actas de Asignación')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-file-earmark-text me-2"></i>Actas de Asignación
                </h1>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-check me-2"></i>Seleccionar Usuario
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('actas.vista-previa') }}" method="GET" id="formActa">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="user_id" class="form-label">Usuario <span class="text-danger">*</span></label>
                                        <select class="form-select" id="user_id" name="user_id" required>
                                            <option value="">Seleccionar usuario...</option>
                                            @foreach($usuarios as $usuario)
                                                <option value="{{ $usuario->id }}" 
                                                    data-activos="{{ $usuario->asignaciones_activas_count }}">
                                                    {{ $usuario->name }} ({{ $usuario->documento }}) - {{ $usuario->asignaciones_activas_count }} activos
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="fecha_acta" class="form-label">Fecha del Acta <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="fecha_acta" name="fecha_acta" 
                                               value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="firmado_por" class="form-label">Firmado por <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="firmado_por" name="firmado_por" 
                                               placeholder="Nombre completo del firmante" required>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="cargo_firmante" class="form-label">Cargo del Firmante <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="cargo_firmante" name="cargo_firmante" 
                                               placeholder="Ej: Jefe de Recursos Humanos" required>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-eye me-2"></i>Vista Previa
                                    </button>
                                    <button type="button" class="btn btn-success" id="btnGenerarPDF" disabled>
                                        <i class="bi bi-file-earmark-pdf me-2"></i>Generar PDF
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2"></i>Información
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">Instrucciones:</h6>
                                <ol class="mb-0 small">
                                    <li>Selecciona el usuario para generar el acta</li>
                                    <li>Completa la fecha del acta y datos del firmante</li>
                                    <li>Usa "Vista Previa" para revisar el contenido</li>
                                    <li>Genera el PDF cuando esté listo</li>
                                </ol>
                            </div>

                            <div class="alert alert-warning">
                                <h6 class="alert-heading">Nota:</h6>
                                <p class="mb-0 small">
                                    Solo se mostrarán usuarios que tengan activos asignados actualmente.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-graph-up me-2"></i>Estadísticas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border rounded p-2">
                                        <h5 class="text-primary mb-0">{{ $usuarios->count() }}</h5>
                                        <small>Usuarios con Activos</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-2">
                                        <h5 class="text-success mb-0">{{ $usuarios->sum('asignaciones_activas_count') }}</h5>
                                        <small>Total Asignaciones</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formActa');
    const btnGenerarPDF = document.getElementById('btnGenerarPDF');
    const userId = document.getElementById('user_id');

    // Habilitar botón de generar PDF cuando se seleccione un usuario
    userId.addEventListener('change', function() {
        btnGenerarPDF.disabled = !this.value;
    });

    // Manejar generación de PDF
    btnGenerarPDF.addEventListener('click', function() {
        if (userId.value) {
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            window.open(`{{ route('actas.generar') }}?${params.toString()}`, '_blank');
        }
    });
});
</script>
@endsection

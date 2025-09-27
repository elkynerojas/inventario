@extends('layouts.app')

@section('title', 'Importar Usuarios - Sistema de Inventario')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Header -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="bi bi-upload"></i> Importar Usuarios</h2>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver a Usuarios
                    </a>
                </div>
            </div>
        </div>

        <!-- Mensajes de éxito/error -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Errores de importación -->
        @if (session('errores') && count(session('errores')) > 0)
            <div class="alert alert-danger">
                <h5><i class="bi bi-exclamation-triangle"></i> Errores encontrados:</h5>
                <ul class="mb-0">
                    @foreach (session('errores') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <!-- Formulario de importación -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-file-earmark-excel"></i> Subir Archivo Excel</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('usuarios.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="archivo" class="form-label">Seleccionar archivo Excel/CSV</label>
                                <input type="file" 
                                       class="form-control @error('archivo') is-invalid @enderror" 
                                       id="archivo" 
                                       name="archivo" 
                                       accept=".xlsx,.xls,.csv"
                                       required>
                                @error('archivo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Formatos soportados: .xlsx, .xls, .csv (máximo 10MB)
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary me-md-2">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-upload"></i> Importar Usuarios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Instrucciones -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Instrucciones</h5>
                    </div>
                    <div class="card-body">
                        <h6>Formato del archivo:</h6>
                        <p>El archivo debe tener las siguientes columnas en la primera fila:</p>
                        
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Columna</th>
                                        <th>Obligatorio</th>
                                        <th>Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>nombre</code></td>
                                        <td><span class="badge bg-danger">Sí</span></td>
                                        <td>Nombre completo del usuario</td>
                                    </tr>
                                    <tr>
                                        <td><code>email</code></td>
                                        <td><span class="badge bg-danger">Sí</span></td>
                                        <td>Correo electrónico único</td>
                                    </tr>
                                    <tr>
                                        <td><code>documento</code></td>
                                        <td><span class="badge bg-danger">Sí</span></td>
                                        <td>Número de documento (5-20 caracteres, solo números y letras)</td>
                                    </tr>
                                    <tr>
                                        <td><code>rol</code></td>
                                        <td><span class="badge bg-secondary">No</span></td>
                                        <td>admin, estudiante, profesor, administrativo, otro</td>
                                    </tr>
                                    <tr>
                                        <td><code>password</code></td>
                                        <td><span class="badge bg-secondary">No</span></td>
                                        <td>Contraseña (por defecto: el mismo documento)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-info mt-3">
                            <h6><i class="bi bi-lightbulb"></i> Consejos:</h6>
                            <ul class="mb-0">
                                <li>El documento es obligatorio y debe ser único</li>
                                <li>El documento debe tener entre 5 y 20 caracteres</li>
                                <li>El documento solo puede contener números y letras (sin espacios)</li>
                                <li>Si no especificas un rol, se asignará "estudiante" por defecto</li>
                                <li>Si no especificas una contraseña, se usará el mismo documento</li>
                                <li>Los emails y documentos deben ser únicos</li>
                                <li>Los roles deben existir en la base de datos</li>
                                <li>Las filas con errores se omitirán automáticamente</li>
                            </ul>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('usuarios.template') }}" 
                               class="btn btn-outline-primary btn-sm" 
                               download>
                                <i class="bi bi-download"></i> Descargar Plantilla
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ejemplo de archivo -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-table"></i> Ejemplo de Archivo</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>nombre</th>
                                        <th>email</th>
                                        <th>documento</th>
                                        <th>rol</th>
                                        <th>password</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Juan Pérez</td>
                                        <td>juan.perez@email.com</td>
                                        <td>12345678</td>
                                        <td>estudiante</td>
                                        <td>mipassword123</td>
                                    </tr>
                                    <tr>
                                        <td>María García</td>
                                        <td>maria.garcia@email.com</td>
                                        <td>87654321</td>
                                        <td>profesor</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Carlos López</td>
                                        <td>carlos.lopez@email.com</td>
                                        <td>CC12345678</td>
                                        <td>admin</td>
                                        <td>admin123</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

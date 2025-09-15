<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Inventario')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #dc3545;
            --primary-dark: #c82333;
            --primary-light: #e74c3c;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
            --light-bg: #f8f9fa;
            --white: #ffffff;
            --border-radius: 12px;
            --shadow: 0 4px 15px rgba(0,0,0,0.1);
            --shadow-hover: 0 8px 25px rgba(0,0,0,0.15);
            --transition: all 0.3s ease;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .header-app {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            box-shadow: var(--shadow-hover);
            padding: 1.5rem 0;
            position: relative;
            overflow: hidden;
        }

        .header-app img {
            max-width: 80px;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            border: 3px solid rgba(255,255,255,0.2);
        }

        .header-app h1 {
            color: var(--white);
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            margin: 0;
            font-size: 2.2rem;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            box-shadow: var(--shadow);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--white);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            color: var(--white);
        }

        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .table-responsive {
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        .table thead th {
            background: var(--primary-color) !important;
            color: var(--white) !important;
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .badge-estado {
            padding: 0.5em 1em;
            font-weight: 600;
            border-radius: 20px;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-estado.bueno {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .badge-estado.regular {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: #212529;
        }

        .badge-estado.malo {
            background: linear-gradient(135deg, #dc3545, #e83e8c);
            color: white;
        }

        .badge-estado.dado\ de\ baja {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
        }

        /* ===== ESTILOS PERSONALIZADOS PARA PAGINACIÓN ===== */
        
        /* Contenedor principal de paginación */
        .pagination {
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Enlaces de página */
        .pagination .page-link {
            border: 1px solid #e3e6f0;
            color: #5a5c69;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.5rem;
            margin: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            min-width: 2.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* Efecto hover mejorado */
        .pagination .page-link:hover {
            background: linear-gradient(135deg, #f8f9fc 0%, #e3e6f0 100%);
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.15);
        }

        /* Página activa */
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-color: var(--primary-color);
            color: white;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
            transform: translateY(-1px);
        }

        /* Página deshabilitada */
        .pagination .page-item.disabled .page-link {
            color: #adb5bd;
            background: linear-gradient(135deg, #f8f9fc 0%, #e9ecef 100%);
            border-color: #e9ecef;
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Paginación pequeña */
        .pagination-sm .page-link {
            padding: 0.375rem 0.625rem;
            font-size: 0.8rem;
            min-width: 2rem;
            border-radius: 0.375rem;
        }

        /* Flechas de navegación */
        .pagination-arrow {
            font-size: 1rem;
            font-weight: 600;
            line-height: 1;
            display: inline-block;
            vertical-align: middle;
            color: inherit;
        }

        /* Efecto especial para flechas */
        .pagination .page-link:hover .pagination-arrow {
            transform: scale(1.1);
            transition: transform 0.2s ease;
        }

        /* Información de resultados */
        .pagination-info {
            color: #6c757d;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .pagination-info .fw-semibold {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Contenedor de paginación con fondo */
        .card-footer {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%);
            border-top: 1px solid #e3e6f0;
            padding: 1.5rem;
        }

        /* Responsive para móviles */
        @media (max-width: 576px) {
            .pagination {
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .pagination-sm .page-link {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
                min-width: 1.75rem;
            }
            
            .pagination-info {
                font-size: 0.8rem;
                text-align: center;
                margin-bottom: 1rem;
            }
        }

        /* Animación de carga para paginación */
        .pagination .page-link:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Separadores de puntos */
        .pagination .page-item.disabled .page-link {
            background: transparent;
            border: none;
            color: #adb5bd;
            font-weight: bold;
            padding: 0.5rem 0.25rem;
        }

        /* ===== ESTILOS ADICIONALES PARA TABLAS ===== */
        
        /* Mejoras en las tablas */
        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background: linear-gradient(135deg, #f8f9fc 0%, #e3e6f0 100%);
            border-bottom: 2px solid #e3e6f0;
            font-weight: 600;
            color: #5a5c69;
            padding: 1rem 0.75rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(220, 53, 69, 0.05);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .table tbody td {
            padding: 0.875rem 0.75rem;
            border-bottom: 1px solid #f8f9fc;
            vertical-align: middle;
        }

        /* Mejoras en los botones de acción */
        .btn-group .btn {
            border-radius: 0.375rem;
            margin: 0 1px;
            transition: all 0.2s ease;
        }

        .btn-group .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        /* Efecto de carga suave */
        .table-responsive {
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
    @stack('styles')
    </head>
<body>
    <!-- Header -->
    <div class="header-app">
        <div class="container text-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Institución" onerror="this.style.display='none'">
            <h1>Sistema de Inventario</h1>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-box-seam"></i> Sistema de Inventario
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('activos.index') }}">
                            <i class="bi bi-list-ul"></i> Activos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('asignaciones.index') }}">
                            <i class="bi bi-person-check"></i> Asignaciones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reportes.index') }}">
                            <i class="bi bi-graph-up"></i> Reportes
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
                    </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    </body>
</html>
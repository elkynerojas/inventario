<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sistema de Inventario - {{ colegio_nombre() ?? 'RedSoft' }}</title>
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            .gradient-bg {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .hero-pattern {
                background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            }
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
            .floating {
                animation: float 6s ease-in-out infinite;
            }
        </style>
    </head>

    <body class="bg-light">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <img src="{{ colegio_logo() }}" alt="Logo" class="me-2" style="height: 40px;">
                    <h1 class="h5 mb-0 fw-bold text-danger">
                        {{ colegio_nombre() ?? 'Sistema de Inventario' }}
                    </h1>
                </a>
                
                <div class="d-flex gap-2">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                            </a>
                            
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="gradient-bg hero-pattern text-white py-5">
            <div class="container">
                <div class="row align-items-center min-vh-50">
                    <div class="col-lg-6 text-center text-lg-start">
                        <h1 class="display-4 fw-bold mb-4 text-danger">
                            Sistema de Inventario Inteligente
                        </h1>
                        <p class="lead mb-4 text-danger">
                            Gestiona todos tus activos de manera eficiente con nuestra plataforma integral de inventario.
                        </p>
                        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg px-4 py-3 fw-semibold">
                                        <i class="bi bi-speedometer2 me-2"></i>Ir al Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-light btn-lg px-4 py-3 fw-semibold">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Comenzar Ahora
                                    </a>
                                @endauth
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 text-center">
                        <div class="floating">
                            <i class="bi bi-box-seam display-1 text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center mb-5">
                        <h2 class="display-5 fw-bold text-dark mb-4">
                            Características Principales
                        </h2>
                        <p class="lead text-muted">
                            Todo lo que necesitas para gestionar tu inventario de manera profesional
                        </p>
                    </div>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-list-ul text-primary display-4 mb-3"></i>
                                <h3 class="h5 fw-semibold text-dark mb-3">Gestión de Activos</h3>
                                <p class="text-muted">
                                    Registra, categoriza y mantén un control completo de todos tus activos con información detallada.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-person-check text-primary display-4 mb-3"></i>
                                <h3 class="h5 fw-semibold text-dark mb-3">Asignaciones</h3>
                                <p class="text-muted">
                                    Asigna activos a usuarios específicos y mantén un historial completo de todas las asignaciones.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-graph-up text-primary display-4 mb-3"></i>
                                <h3 class="h5 fw-semibold text-dark mb-3">Reportes Detallados</h3>
                                <p class="text-muted">
                                    Genera reportes completos y estadísticas para tomar decisiones informadas sobre tu inventario.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-trash text-primary display-4 mb-3"></i>
                                <h3 class="h5 fw-semibold text-dark mb-3">Control de Bajas</h3>
                                <p class="text-muted">
                                    Registra bajas de activos con documentación completa y seguimiento de procesos.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-gear text-primary display-4 mb-3"></i>
                                <h3 class="h5 fw-semibold text-dark mb-3">Configuración Flexible</h3>
                                <p class="text-muted">
                                    Personaliza el sistema según las necesidades específicas de tu organización.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-shield-lock text-primary display-4 mb-3"></i>
                                <h3 class="h5 fw-semibold text-dark mb-3">Seguridad Total</h3>
                                <p class="text-muted">
                                    Sistema seguro con roles y permisos para proteger la información de tu inventario.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="bg-dark text-white py-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <h2 class="display-5 fw-bold mb-4">
                            ¿Listo para Optimizar tu Inventario?
                        </h2>
                        <p class="lead text-light mb-4">
                            Únete a las organizaciones que ya están gestionando su inventario de manera eficiente.
                        </p>
                        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-4 py-3 fw-semibold">
                                        <i class="bi bi-speedometer2 me-2"></i>Ir al Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4 py-3 fw-semibold">
                                        <i class="bi bi-person-plus me-2"></i>Comenzar Gratis
                                    </a>
                                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4 py-3 fw-semibold">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                                    </a>
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-dark text-white py-4">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="text-light mb-2">
                            <strong>{{ colegio_nombre() ?? 'Sistema de Inventario' }}</strong>
                        </p>
                        <div class="text-muted small">
                            © {{ date('Y') }} Todos los derechos reservados. Desarrollado con Laravel y Bootstrap.
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
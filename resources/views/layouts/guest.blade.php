<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Iniciar Sesión - {{ colegio_nombre() ?? 'Sistema de Inventario' }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .login-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body class="gradient-bg login-pattern">
    <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-5">
        <!-- Logo y Nombre del Colegio -->
        <div class="text-center mb-4">
            <div class="mb-3">

                <img src="{{ colegio_logo() }}" alt="Logo {{ colegio_nombre() }}" class="mx-auto" style="height: 80px; width: auto;">

            </div>
            <h1 class="h3 fw-bold text-danger mb-2">
                {{ colegio_nombre() ?? 'Sistema de Inventario' }}
            </h1>
            <p class="text-white-50 small">Acceso al Sistema</p>
        </div>

        <!-- Formulario de Login -->
        <div class="w-100 login-card shadow-lg rounded-3 overflow-hidden" style="max-width: 400px;">
            <div class="p-4">
                {{ $slot }}
            </div>
        </div>

        <!-- Enlace de regreso -->
        <div class="mt-4">
            <a href="/" class="text-white-50 text-decoration-none small">
                <i class="bi bi-arrow-left"></i> Volver al inicio
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
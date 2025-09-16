<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sistema de Inventario - {{ colegio_nombre() ?? 'RedSoft' }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
                tailwind.config = {
                    theme: {
                        extend: {
                            fontFamily: {
                                'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
                            },
                            colors: {
                                primary: {
                                    50: '#eff6ff',
                                    100: '#dbeafe',
                                    200: '#bfdbfe',
                                    300: '#93c5fd',
                                    400: '#60a5fa',
                                    500: '#3b82f6',
                                    600: '#2563eb',
                                    700: '#1d4ed8',
                                    800: '#1e40af',
                                    900: '#1e3a8a',
                                }
                            }
                        }
                    }
                }
            </script>
        @endif
        
        <style>
            .gradient-bg {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .hero-pattern {
                background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            }
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-xl font-bold text-gray-900">
                                {{ colegio_nombre() ?? 'RedSoft' }}
                            </h1>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" 
                                   class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition duration-200">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                                    Iniciar Sesión
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" 
                                       class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition duration-200">
                                        Registrarse
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="gradient-bg hero-pattern relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                        Sistema de Inventario
                        <span class="block text-yellow-300">Inteligente</span>
                    </h1>
                    <p class="text-xl md:text-2xl text-white/90 mb-8 max-w-3xl mx-auto">
                        Gestiona todos tus activos de manera eficiente con nuestro sistema completo de inventario. 
                        Control total, reportes detallados y seguimiento en tiempo real.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        @auth
                            <a href="{{ url('/dashboard') }}" 
                               class="bg-white text-primary-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition duration-200 shadow-lg">
                                Ir al Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="bg-white text-primary-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition duration-200 shadow-lg">
                                Iniciar Sesión
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" 
                                   class="bg-yellow-400 text-gray-900 px-8 py-4 rounded-lg font-semibold hover:bg-yellow-300 transition duration-200 shadow-lg">
                                    Crear Cuenta
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
            
            <!-- Floating Elements -->
            <div class="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full animate-float"></div>
            <div class="absolute top-40 right-20 w-16 h-16 bg-yellow-300/20 rounded-full animate-float" style="animation-delay: 2s;"></div>
            <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-white/10 rounded-full animate-float" style="animation-delay: 4s;"></div>
        </section>

        <!-- Features Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Características Principales
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Herramientas poderosas para una gestión completa de inventario
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-gray-50 p-8 rounded-xl hover:shadow-lg transition duration-300">
                        <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Gestión de Activos</h3>
                        <p class="text-gray-600">
                            Registra, categoriza y mantén un control detallado de todos tus activos con información completa y actualizada.
                        </p>
                    </div>
                    
                    <!-- Feature 2 -->
                    <div class="bg-gray-50 p-8 rounded-xl hover:shadow-lg transition duration-300">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Asignaciones</h3>
                        <p class="text-gray-600">
                            Asigna activos a usuarios específicos con seguimiento completo del historial y estado de cada asignación.
                        </p>
                    </div>
                    
                    <!-- Feature 3 -->
                    <div class="bg-gray-50 p-8 rounded-xl hover:shadow-lg transition duration-300">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Reportes Detallados</h3>
                        <p class="text-gray-600">
                            Genera reportes completos en PDF y Excel con estadísticas detalladas y análisis de tu inventario.
                        </p>
                    </div>
                    
                    <!-- Feature 4 -->
                    <div class="bg-gray-50 p-8 rounded-xl hover:shadow-lg transition duration-300">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Control de Bajas</h3>
                        <p class="text-gray-600">
                            Registra y gestiona las bajas de activos con documentación completa y actas de baja.
                        </p>
                    </div>
                    
                    <!-- Feature 5 -->
                    <div class="bg-gray-50 p-8 rounded-xl hover:shadow-lg transition duration-300">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Configuración Flexible</h3>
                        <p class="text-gray-600">
                            Personaliza categorías, tipos de activos y configuraciones según las necesidades de tu organización.
                        </p>
                    </div>
                    
                    <!-- Feature 6 -->
                    <div class="bg-gray-50 p-8 rounded-xl hover:shadow-lg transition duration-300">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Seguridad Total</h3>
                        <p class="text-gray-600">
                            Sistema seguro con autenticación robusta y control de acceso basado en roles de usuario.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-20 bg-primary-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                        Confiado por Organizaciones
                    </h2>
                    <p class="text-xl text-white/90">
                        Miles de activos gestionados exitosamente
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-white mb-2">100%</div>
                        <div class="text-white/80">Control de Activos</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-white mb-2">24/7</div>
                        <div class="text-white/80">Disponibilidad</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-white mb-2">0</div>
                        <div class="text-white/80">Pérdidas de Datos</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-white mb-2">∞</div>
                        <div class="text-white/80">Escalabilidad</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                    ¿Listo para Optimizar tu Inventario?
                </h2>
                <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                    Únete a las organizaciones que ya están gestionando sus activos de manera eficiente y profesional.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="bg-primary-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-primary-700 transition duration-200 shadow-lg">
                            Acceder al Sistema
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="bg-primary-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-primary-700 transition duration-200 shadow-lg">
                            Iniciar Sesión
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="bg-white text-gray-900 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition duration-200 shadow-lg">
                                Crear Cuenta Gratis
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-800 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h3 class="text-xl font-bold text-white mb-4">
                        {{ colegio_nombre() ?? 'RedSoft' }}
                    </h3>
                    <p class="text-gray-400 mb-6">
                        Sistema de Inventario Inteligente
                    </p>
                    <div class="text-gray-500 text-sm">
                        <p>Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</p>
                        <p class="mt-2">© {{ date('Y') }} Todos los derechos reservados</p>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>

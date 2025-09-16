<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Título del formulario -->
    <div class="text-center mb-4">
        <h2 class="h4 fw-bold text-dark mb-2">Iniciar Sesión</h2>
        <p class="text-muted small">Ingresa tus credenciales para acceder al sistema</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
            <input id="email" 
                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   autocomplete="username"
                   placeholder="tu@email.com">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Contraseña</label>
            <input id="password" 
                   class="form-control form-control-lg @error('password') is-invalid @enderror"
                   type="password"
                   name="password"
                   required 
                   autocomplete="current-password"
                   placeholder="••••••••">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" 
                       type="checkbox" 
                       id="remember_me" 
                       name="remember">
                <label class="form-check-label text-muted small" for="remember_me">
                    Recordarme
                </label>
            </div>
            
            @if (Route::has('password.request'))
                <a class="text-decoration-none small" 
                   href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
            </button>
        </div>
    </form>

    <!-- Enlaces adicionales -->
    <div class="text-center">
        @if (Route::has('register'))
            <p class="text-muted small mb-0">
                ¿No tienes una cuenta? 
                <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">
                    Regístrate aquí
                </a>
            </p>
        @endif
    </div>
</x-guest-layout>

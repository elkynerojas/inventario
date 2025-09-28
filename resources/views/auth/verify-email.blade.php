<x-guest-layout>
    <!-- Título del formulario -->
    <div class="text-center mb-4">
        <h2 class="h4 fw-bold text-dark mb-2">Verificar Correo Electrónico</h2>
        <p class="text-muted small">Confirma tu dirección de correo para continuar</p>
    </div>

    <div class="alert alert-info mb-4">
        <i class="bi bi-envelope-check me-2"></i>
        ¡Gracias por registrarte! Antes de comenzar, ¿podrías verificar tu dirección de correo electrónico haciendo clic en el enlace que acabamos de enviarte? Si no recibiste el correo, te enviaremos otro con gusto.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mb-4">
            <i class="bi bi-check-circle me-2"></i>
            Se ha enviado un nuevo enlace de verificación a la dirección de correo electrónico que proporcionaste durante el registro.
        </div>
    @endif

    <div class="d-grid gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary btn-lg fw-semibold w-100">
                <i class="bi bi-envelope me-2"></i>Reenviar Correo de Verificación
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary w-100">
                <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
            </button>
        </form>
    </div>
</x-guest-layout>
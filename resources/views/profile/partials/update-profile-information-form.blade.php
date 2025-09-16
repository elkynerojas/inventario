<section>
    <header class="mb-4">
        <h3 class="h5 fw-bold text-dark">
            Información del Perfil
        </h3>
        <p class="text-muted small mb-0">
            Actualiza la información de tu perfil y dirección de correo electrónico.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Nombre</label>
            <input id="name" 
                   name="name" 
                   type="text" 
                   class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name', $user->name) }}" 
                   required 
                   autofocus 
                   autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
            <input id="email" 
                   name="email" 
                   type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   value="{{ old('email', $user->email) }}" 
                   required 
                   autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-muted small">
                        Tu dirección de correo electrónico no está verificada.
                        <button form="send-verification" class="btn btn-link p-0 text-decoration-none">
                            Haz clic aquí para reenviar el correo de verificación.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success alert-sm">
                            Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-2"></i>Guardar
            </button>

            @if (session('status') === 'profile-updated')
                <div class="alert alert-success alert-sm mb-0">
                    <i class="bi bi-check-circle me-1"></i>Guardado.
                </div>
            @endif
        </div>
    </form>
</section>

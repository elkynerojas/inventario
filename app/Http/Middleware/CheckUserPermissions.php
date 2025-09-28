<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Si no hay usuario autenticado, redirigir al login
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Si es administrador, permitir acceso completo
        if ($user->esAdmin()) {
            return $next($request);
        }
        
        // Para usuarios no administradores, verificar si tienen acceso básico
        if (!$user->rol) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }
        
        // Permitir acceso a usuarios con roles válidos
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
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
        
        // Si no es administrador, denegar acceso
        if (!$user->esAdmin()) {
            abort(403, 'No tienes permisos de administrador para acceder a esta sección.');
        }
        
        return $next($request);
    }
}

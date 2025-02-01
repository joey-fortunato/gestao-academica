<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Verifica se o usuário está autenticado e tem a role 'admin'
         if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Redireciona usuários não autorizados
        return redirect('/');
    }
}

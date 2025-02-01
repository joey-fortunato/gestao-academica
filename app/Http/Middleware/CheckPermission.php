<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário está autenticado
        if (!Auth::check()) {
            return redirect()->route('/admin/dashboard');
        }

        // Verifica se o usuário tem a permissão necessária
        $user = Auth::user();
        if (!$user->hasPermission($permission)) {
            abort(403, 'Acesso não autorizado.');
        }
        return $next($request);
    }
}

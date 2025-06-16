<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles  // MODIFICADO: Acepta múltiples roles como argumentos separados.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var User $user */
        $user = Auth::user();

        if (!$user->active) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'ci' => 'Su cuenta ha sido desactivada. Contacte al administrador.'
            ]);
        }

        if ($user->isTemporarilyLocked()) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'ci' => 'Su cuenta está temporalmente bloqueada.'
            ]);
        }

        // MODIFICADO: Verificar si el rol del usuario está en la lista de roles permitidos.
        $userRole = strtolower($user->role_name ?? optional($user->rol)->nombre_rol);
        $allowedRoles = array_map('strtolower', $roles);

        if (!in_array($userRole, $allowedRoles)) {
            Log::warning("Usuario {$user->ci} con rol '{$userRole}' intentó acceder a recurso que requiere uno de los siguientes roles: " . implode(', ', $allowedRoles));
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}

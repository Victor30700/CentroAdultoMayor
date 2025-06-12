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
     * @param  \Illuminate\Http\Request        $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string                          $role   Ejemplo: "admin", "legal", "responsable" o "asistente-social"
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1) Verificar que el usuario esté autenticado
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        /** @var User $user */
        $user = Auth::user(); // Con el doc-block arriba, Intelephense sabe que $user es App\Models\User

        // 2) Verificar que el usuario esté activo
        if (! $user->active) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'ci' => 'Su cuenta ha sido desactivada. Contacte al administrador.'
            ]);
        }

        // 3) Verificar que el usuario no esté bloqueado temporalmente
        if ($user->isTemporarilyLocked()) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'ci' => 'Su cuenta está temporalmente bloqueada.'
            ]);
        }

        // 4) Verificar el rol del usuario
        $userRole     = strtolower($user->role_name);
        $requiredRole = strtolower($role);

        if ($userRole !== $requiredRole) {
            Log::warning("Usuario {$user->ci} intentó acceder a recurso que requiere rol '{$role}' pero tiene rol '{$userRole}'");
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}

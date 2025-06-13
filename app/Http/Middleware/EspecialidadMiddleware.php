<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class EspecialidadMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $especialidad  La especialidad requerida (ej. "Enfermeria", "Fisioterapia").
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $especialidad): Response
    {
        // 1. Asegurarse de que el usuario esté autenticado.
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var User $user */
        $user = Auth::user();

        // 2. Verificar que el rol del usuario sea 'responsable'.
        //    Este middleware está diseñado específicamente para este rol.
        if (strtolower($user->rol->nombre_rol) !== 'responsable') {
            Log::warning("Acceso denegado: El usuario {$user->ci} con rol '{$user->rol->nombre_rol}' intentó acceder a una ruta protegida por especialidad.");
            abort(403, 'Acción no autorizada.');
        }

        // 3. Verificar que el usuario tenga una persona asociada y una especialidad definida.
        if (!$user->persona || !$user->persona->area_especialidad) {
            Log::warning("Acceso denegado: El usuario responsable {$user->ci} no tiene una especialidad asignada.");
            abort(403, 'No tienes una especialidad asignada para acceder a este recurso.');
        }
        
        // 4. Comparar la especialidad del usuario con la requerida por la ruta.
        $userEspecialidad = $user->persona->area_especialidad;
        if ($userEspecialidad !== $especialidad) {
            Log::warning("Acceso denegado: El usuario {$user->ci} con especialidad '{$userEspecialidad}' intentó acceder a un recurso para '{$especialidad}'.");
            abort(403, 'No tienes la especialidad requerida para esta sección.');
        }

        // 5. Si todas las validaciones pasan, permitir el acceso.
        return $next($request);
    }
}
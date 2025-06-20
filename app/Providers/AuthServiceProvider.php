<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Log; // Importamos Log para depuración

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // --- INICIO DE LA CORRECCIÓN ---
        // Gate global: si el usuario es 'admin', autoriza TODO automáticamente.
        // Verificamos si el usuario tiene el rol 'admin' a través de su nombre_rol.
        // Esta es la forma más robusta de asegurar que el admin siempre tenga todos los permisos.
        Gate::before(function (User $user, $ability) {
            // La función with('rol') carga la relación para no hacer una consulta extra.
            if ($user->load('rol')->rol && $user->rol->nombre_rol === 'admin') {
                return true;
            }
        });
        // --- FIN DE LA CORRECCIÓN ---

        // Definir dinámicamente un Gate por cada permiso en la BD
        // Para que @can('nombre_del_permiso') funcione correctamente.
        try {
            if (Schema::hasTable('permissions')) {
                // Obtenemos los permisos y los guardamos en caché para mejorar el rendimiento.
                $permissions = \Illuminate\Support\Facades\Cache::remember('permissions', 3600, function () {
                    return Permission::all();
                });

                $permissions->each(function (Permission $permission) {
                    Gate::define($permission->name, function (User $user) use ($permission) {
                        // El método hasPermission ya debería estar definido en tu modelo User.
                        return $user->hasPermission($permission->name);
                    });
                });
            }
        } catch (\Throwable $e) {
            // Registramos el error en lugar de dejar el bloque catch vacío.
            Log::error("Error al registrar los permisos en AuthServiceProvider: " . $e->getMessage());
        }
    }
}
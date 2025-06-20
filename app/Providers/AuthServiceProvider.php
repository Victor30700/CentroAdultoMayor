<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

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

        // [LA SOLUCIÓN]
        // Gate global que se ejecuta ANTES que cualquier otra regla.
        // Si el usuario tiene el rol 'admin', la función devuelve 'true' inmediatamente
        // y Laravel concede el acceso sin verificar nada más.
        // Esto soluciona el problema de raíz y es la práctica recomendada para super-admins.
        Gate::before(function (User $user, $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }
        });

        // El resto de los Gates se definen para los demás roles que no son 'admin'.
        try {
            if (Schema::hasTable('permissions')) {
                // Usamos caché para no consultar la BD en cada petición.
                $permissions = Cache::rememberForever('permissions', function () {
                    return Permission::all();
                });

                foreach ($permissions as $permission) {
                    Gate::define($permission->name, function (User $user) use ($permission) {
                        return $user->hasPermission($permission->name);
                    });
                }
            }
        } catch (\Throwable $e) {
            // En caso de error, no hacer nada para no romper la aplicación.
        }
    }
}
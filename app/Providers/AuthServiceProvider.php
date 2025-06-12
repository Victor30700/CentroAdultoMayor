<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;
use App\Models\User;

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

        // Gate global: si el usuario es 'admin', autoriza TODO automáticamente
        Gate::before(function (User $user, $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }
        });

        // Definir dinámicamente un Gate por cada permiso en la BD
        // Para que @can('nombre_del_permiso') funcione correctamente.
        try {
            if (Schema::hasTable('permissions')) {
                Permission::all()->each(function (Permission $permission) {
                    Gate::define($permission->name, function (User $user) use ($permission) {
                        return $user->hasPermission($permission->name);
                    });
                });
            }
        } catch (\Throwable $e) {
            // Puede suceder cuando la tabla 'permissions' aún no existe (primeras migraciones)
        }
    }
}

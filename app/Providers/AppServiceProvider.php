<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;
use App\Models\User;
use App\Http\Middleware\RoleMiddleware;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1) Registrar el middleware 'role'
        Route::aliasMiddleware('role', RoleMiddleware::class);

        // 2) Gate global que da acceso total si el usuario tiene rol 'admin'
        Gate::before(function (User $user, $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }
        });

        // 3) Definir dinámicamente Gates para cada permiso existente en la BD
        //    De este modo, usando @can('nombre.del.permiso') en Blade funcionará correctamente.
        try {
            if (Schema::hasTable('permissions')) {
                Permission::all()->each(function (Permission $perm) {
                    Gate::define($perm->name, function (User $user) use ($perm) {
                        return $user->hasPermission($perm->name);
                    });
                });
            }
        } catch (\Throwable $e) {
            // Puede ocurrir durante migraciones iniciales si la tabla 'permissions' no existe todavía.
        }
        
    }
}

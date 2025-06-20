<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        try {
            if (Schema::hasTable('permissions')) {
                // El uso de `all()` puede ser ineficiente si hay muchos permisos,
                // pero para este caso es aceptable. Se define cada permiso.
                foreach (Permission::all() as $permission) {
                    Gate::define($permission->name, function (User $user) use ($permission) {
                        $hasPermission = $user->hasPermission($permission->name);
                        
                        // LOG DE DEBUG: Escribe en storage/logs/laravel.log
                        Log::info("Verificación de Permiso Específico para Usuario ID: {$user->id_usuario}", [
                            'permiso' => $permission->name,
                            'resultado' => $hasPermission ? 'CONCEDIDO' : 'DENEGADO'
                        ]);
                        
                        return $hasPermission;
                    });
                }

                // Gate::before se ejecuta antes que cualquier otra verificación.
                // Ideal para dar acceso total a un super-administrador.
                Gate::before(function (User $user, $ability) {
                    // LOG DE DEBUG: Escribe en storage/logs/laravel.log
                    Log::info("Gate::before para Usuario ID: {$user->id_usuario} (Rol: " . optional($user->rol)->nombre_rol . ")", [
                        'habilidad_requerida' => $ability
                    ]);

                    if ($user->hasRole('Admin')) {
                        Log::info("Resultado de Gate::before: CONCEDIDO (El usuario es Admin).");
                        return true; // Acceso concedido, no se verifican otras reglas.
                    }
                    
                    Log::info("Resultado de Gate::before: NULL (El usuario no es Admin, se procede a verificar permiso específico).");
                    return null; // Dejar que las reglas específicas del Gate decidan.
                });
            }
        } catch (\Exception $e) {
            Log::error("Error Crítico al registrar Gates de permisos en AuthServiceProvider: " . $e->getMessage());
        }
    }
}
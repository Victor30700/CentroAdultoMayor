<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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

        // ESTE ES EL CÓDIGO DE DIAGNÓSTICO
        Gate::before(function (User $user, $ability) {
            // dd() detiene todo y nos muestra los valores.
            dd(
                'Usuario Autenticado:', $user->toArray(),
                'Rol del Usuario:', optional($user->rol)->nombre_rol,
                '¿Tiene rol de admin?:', $user->hasRole('admin'),
                'Permiso que se está revisando:', $ability
            );

            // El resto del código no se ejecutará por el dd()
            if ($user->hasRole('admin')) {
                return true;
            }
        });
    }
}
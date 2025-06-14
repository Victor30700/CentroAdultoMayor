<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;

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

        /**
         * El Gate 'before' se ejecuta antes que cualquier otro.
         * Si el usuario es 'admin', le damos acceso a todo y no se comprueba nada más.
         * Esto simplifica enormemente el resto de las definiciones de Gates.
         */
        Gate::before(function (User $user, $ability) {
            if ($user->rol->nombre_rol === 'admin') {
                return true;
            }
        });

        //--- GATES PARA USUARIO LEGAL ---//
        Gate::define('access-legal', function(User $user) {
            return $user->rol->nombre_rol === 'legal';
        });

        Gate::define('gestionar-adulto-mayor', function(User $user) {
            // Accesible por Legal y Asistente Social
            return in_array($user->rol->nombre_rol, ['legal', 'asistente-social']);
        });

        Gate::define('modulo-proteccion', function(User $user) {
            return $user->rol->nombre_rol === 'legal';
        });

        //--- GATES PARA USUARIO RESPONSABLE (CON ESPECIALIDAD) ---//
        Gate::define('access-responsable', function(User $user) {
            return $user->rol->nombre_rol === 'responsable';
        });
        
        Gate::define('modulo-medico', function(User $user) {
            // Cualquier responsable tiene acceso al módulo médico general
            return $user->rol->nombre_rol === 'responsable';
        });
        
        Gate::define('access-enfermeria', function(User $user) {
            return $user->rol->nombre_rol === 'responsable' 
                && optional($user->persona)->area_especialidad === 'Enfermeria';
        });

        Gate::define('access-fisioterapia', function(User $user) {
            return $user->rol->nombre_rol === 'responsable' 
                && optional($user->persona)->area_especialidad === 'Fisioterapia';
        });

        Gate::define('access-kinesiologia', function(User $user) {
            return $user->rol->nombre_rol === 'responsable' 
                && optional($user->persona)->area_especialidad === 'Kinesiologia';
        });

        //--- GATES PARA ASISTENTE SOCIAL ---//
        Gate::define('access-asistente-social', function(User $user) {
            return $user->rol->nombre_rol === 'asistente-social';
        });
        
        Gate::define('modulo-orientacion', function(User $user) {
            return $user->rol->nombre_rol === 'asistente-social';
        });
        
        //--- GATES GENERALES PARA CUALQUIER USUARIO AUTENTICADO ---//
        Gate::define('access-dashboard', function(User $user) {
            return in_array($user->rol->nombre_rol, ['admin', 'legal', 'responsable', 'asistente-social']);
        });
    }
}

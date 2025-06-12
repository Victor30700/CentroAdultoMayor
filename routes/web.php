<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GestionarUsuariosController;
use App\Http\Controllers\RegistrarCasoController;
use App\Http\Controllers\GestionarRolesController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Este archivo contiene todas las rutas de la aplicación. Están organizadas
| en dos grupos principales: 'guest' para visitantes y 'auth' para usuarios
| que han iniciado sesión, garantizando una protección robusta.
|
*/

//==========================================================================
// RUTAS PARA INVITADOS (NO AUTENTICADOS)
//==========================================================================
// El middleware 'guest' asegura que estas rutas solo sean accesibles para
// usuarios que NO han iniciado sesión. Si un usuario autenticado intenta
// acceder, Laravel lo redirigirá a '/dashboard' automáticamente.
Route::middleware('guest')->group(function () {
    
    // Ruta de bienvenida principal. Esta es la puerta de entrada para nuevos usuarios.
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    // Rutas para el formulario de inicio de sesión.
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

});


//==========================================================================
// RUTAS PROTEGIDAS (REQUIEREN AUTENTICACIÓN)
//==========================================================================
// El middleware 'auth' es el guardián principal. NINGUNA ruta dentro de
// este grupo puede ser accedida sin haber iniciado sesión previamente.
Route::middleware('auth')->group(function () {

    // Redirige la ruta raíz al dashboard si el usuario ya está autenticado.
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Ruta para cerrar la sesión del usuario.
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard general que actúa como un distribuidor según el rol del usuario.
    Route::get('/dashboard', function () {
        $user = Auth::user();
        
        // Verificación de seguridad: si un usuario no tiene rol, se cierra su sesión.
        if (!$user->role_name && !$user->rol) {
             Log::warning("El usuario con CI {$user->ci} intentó acceder sin un rol asignado.");
             Auth::logout();
             return redirect()->route('login')->withErrors(['ci' => 'No tienes un rol asignado. Contacta al administrador.']);
        }
        
        $roleName = strtolower($user->role_name ?? $user->rol->nombre_rol);

        switch ($roleName) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'responsable':
                return redirect()->route('responsable.dashboard');
            case 'legal':
                return redirect()->route('legal.dashboard');
            case 'asistente-social':
                return redirect()->route('asistente-social.dashboard');
            default:
                Log::warning("Usuario con CI {$user->ci} tiene un rol no reconocido: {$roleName}");
                return view('dashboard'); // Vista genérica por si acaso.
        }
    })->name('dashboard');


    //-----------------------------------------------------
    // RUTAS CON PREFIJO DE ADMINISTRADOR (ACCESO POR ROL O PERMISO)
    //-----------------------------------------------------
    Route::prefix('admin')->name('admin.')->group(function () {

        // Rutas exclusivas para el rol 'admin'
        Route::middleware('role:admin')->group(function () {
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
            Route::get('/users', [AdminController::class, 'listUsers'])->name('users');
            Route::post('/users/{user}/toggle-active', [AdminController::class, 'toggleActive'])->name('users.toggle_active');

            // Formularios de registro
            Route::get('/registrar-asistente-social', [AdminController::class, 'showRegisterAsistenteSocial'])->name('registrar-asistente-social');
            Route::get('/registrar-usuario-legal', [AdminController::class, 'showRegisterLegal'])->name('registrar-usuario-legal');
            Route::get('/registrar-adulto-mayor', [AdminController::class, 'showRegisterAdultoMayor'])->name('registrar-adulto-mayor');
            Route::get('/registrar-responsable-salud', [AdminController::class, 'showRegisterResponsableSalud'])->name('registrar-responsable-salud');

            // Procesamiento de registros
            Route::post('/store-asistente-social', [AdminController::class, 'storeAsistenteSocial'])->name('store-asistente-social');
            Route::post('/store-legal', [AdminController::class, 'storeUsuarioLegal'])->name('store-legal');
            Route::post('/store-adulto-mayor', [AdminController::class, 'storeAdultoMayor'])->name('store-adulto-mayor');
            Route::post('/store-responsable-salud', [AdminController::class, 'storeResponsableSalud'])->name('store-responsable-salud');

            // Rutas para gestionar usuarios (CRUD)
            Route::resource('gestionar-usuarios', GestionarUsuariosController::class)->except(['show']);
            Route::patch('/gestionar-usuarios/{id}/toggle-activity', [GestionarUsuariosController::class, 'toggleActivity'])->name('gestionar-usuarios.toggleActivity');
            
            // Rutas para gestionar roles (CRUD)
            Route::resource('gestionar-roles', GestionarRolesController::class)->except(['show']);
            
            // Rutas para gestionar adultos mayores
            Route::prefix('gestionar-adultos-mayores')->name('gestionar-adultomayor.')->group(function () {
                Route::get('/', [AdminController::class, 'gestionarAdultoMayorIndex'])->name('index');
                Route::get('/buscar', [AdminController::class, 'buscarAdultoMayor'])->name('buscar');
                Route::get('/{ci}/editar', [AdminController::class, 'editarAdultoMayor'])->name('editar');
                Route::put('/{ci}', [AdminController::class, 'actualizarAdultoMayor'])->name('actualizar');
                Route::delete('/{ci}', [AdminController::class, 'eliminarAdultoMayor'])->name('eliminar');
            });
        });

        // Rutas para "Registrar Caso" protegidas por permiso específico.
        // Accesible para 'admin' y cualquier otro rol con el permiso 'modulo.proteccion.registrar'.
        Route::middleware('permission:modulo.proteccion.registrar')->group(function () {
            Route::get('/casos', [RegistrarCasoController::class, 'index'])->name('caso.index');
            Route::get('/caso/{id}', [RegistrarCasoController::class, 'show'])->name('caso.show');
            Route::post('/caso/{id}/completo', [RegistrarCasoController::class, 'storeCompleto'])->name('caso.completo.store');
            Route::get('/caso/{id}/editar', [RegistrarCasoController::class, 'edit'])->name('caso.edit');
            Route::post('/caso/{id}/actualizar', [RegistrarCasoController::class, 'update'])->name('caso.update');
            Route::get('/caso/{id}/ver', [RegistrarCasoController::class, 'showDetalle'])->name('caso.detalle');
        });

        // Aquí puedes agregar más grupos protegidos por otros permisos.
        // Ejemplo: Route::middleware('permission:nombre.del.permiso')->group(...);
    });
    
    //-----------------------------------------------------
    // RUTAS ESPECÍFICAS PARA EL ROL DE RESPONSABLE
    //-----------------------------------------------------
    Route::middleware('role:responsable')->prefix('responsable')->name('responsable.')->group(function () {
        Route::get('/dashboard', function () {
            return view('pages.responsable.dashboard');
        })->name('dashboard');
        // Aquí irían otras rutas para el rol 'responsable'
    });

    //-----------------------------------------------------
    // RUTAS ESPECÍFICAS PARA EL ROL LEGAL
    //-----------------------------------------------------
    Route::middleware('role:legal')->prefix('legal')->name('legal.')->group(function () {
        Route::get('/dashboard', function () {
            return view('pages.legal.dashboard');
        })->name('dashboard');
        // Aquí irían otras rutas para el rol 'legal'
    });

    //-----------------------------------------------------
    // RUTAS ESPECÍFICAS PARA EL ROL DE ASISTENTE SOCIAL
    //-----------------------------------------------------
    Route::middleware('role:asistente-social')->prefix('asistente-social')->name('asistente-social.')->group(function () {
        Route::get('/dashboard', function () {
            return view('pages.asistente-social.dashboard');
        })->name('dashboard');
        // Aquí irían otras rutas para el rol 'asistente-social'
    });

});
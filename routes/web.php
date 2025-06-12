<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GestionarUsuariosController;
use App\Http\Controllers\RegistrarCasoController;
use App\Http\Controllers\GestionarRolesController;
use App\Http\Controllers\ProfileController;
// Se importa el controlador para el rol Legal. Asegúrate de que este archivo exista.
use App\Http\Controllers\Legal\GestionarAdultoMayorController as LegalAdultoMayorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//==========================================================================
// RUTAS PARA INVITADOS (NO AUTENTICADOS)
//==========================================================================
Route::middleware('guest')->group(function () {
    Route::get('/', fn() => view('welcome'))->name('home');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

//==========================================================================
// RUTAS PROTEGIDAS (REQUIEREN AUTENTICACIÓN)
//==========================================================================
Route::middleware('auth')->group(function () {
    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile.show');

    Route::get('/dashboard', function () {
        $user = Auth::user();
        if (!$user->role_name && !$user->rol) {
            Log::warning("El usuario con CI {$user->ci} intentó acceder sin un rol asignado.");
            Auth::logout();
            return redirect()->route('login')->withErrors(['ci' => 'No tienes un rol asignado. Contacta al administrador.']);
        }
        $roleName = strtolower($user->role_name ?? $user->rol->nombre_rol);
        switch ($roleName) {
            case 'admin': return redirect()->route('admin.dashboard');
            case 'responsable': return redirect()->route('responsable.dashboard');
            case 'legal': return redirect()->route('legal.dashboard');
            case 'asistente-social': return redirect()->route('asistente-social.dashboard');
            default:
                Log::warning("Usuario con CI {$user->ci} tiene un rol no reconocido: {$roleName}");
                Auth::logout();
                return redirect()->route('login')->withErrors(['ci' => 'Rol no reconocido.']);
        }
    })->name('dashboard');

    //-----------------------------------------------------
    // RUTAS DE ADMINISTRADOR
    //-----------------------------------------------------
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'listUsers'])->name('users');
        Route::post('/users/{user}/toggle-active', [AdminController::class, 'toggleActive'])->name('users.toggle_active');
        Route::get('/registrar-asistente-social', [AdminController::class, 'showRegisterAsistenteSocial'])->name('registrar-asistente-social');
        Route::get('/registrar-usuario-legal', [AdminController::class, 'showRegisterLegal'])->name('registrar-usuario-legal');
        Route::get('/registrar-adulto-mayor', [AdminController::class, 'showRegisterAdultoMayor'])->name('registrar-adulto-mayor');
        Route::get('/registrar-responsable-salud', [AdminController::class, 'showRegisterResponsableSalud'])->name('registrar-responsable-salud');
        Route::post('/store-asistente-social', [AdminController::class, 'storeAsistenteSocial'])->name('store-asistente-social');
        Route::post('/store-legal', [AdminController::class, 'storeUsuarioLegal'])->name('store-legal');
        Route::post('/store-adulto-mayor', [AdminController::class, 'storeAdultoMayor'])->name('store-adulto-mayor');
        Route::post('/store-responsable-salud', [AdminController::class, 'storeResponsableSalud'])->name('store-responsable-salud');
        Route::resource('gestionar-usuarios', GestionarUsuariosController::class)->except(['show']);
        Route::patch('/gestionar-usuarios/{id}/toggle-activity', [GestionarUsuariosController::class, 'toggleActivity'])->name('gestionar-usuarios.toggleActivity');
        Route::resource('gestionar-roles', GestionarRolesController::class)->except(['show']);
        Route::prefix('gestionar-adultos-mayores')->name('gestionar-adultomayor.')->group(function () {
            Route::get('/', [AdminController::class, 'gestionarAdultoMayorIndex'])->name('index');
            Route::get('/buscar', [AdminController::class, 'buscarAdultoMayor'])->name('buscar');
            Route::get('/{ci}/editar', [AdminController::class, 'editarAdultoMayor'])->name('editar');
            Route::put('/{ci}', [AdminController::class, 'actualizarAdultoMayor'])->name('actualizar');
            Route::delete('/{ci}', [AdminController::class, 'eliminarAdultoMayor'])->name('eliminar');
        });
        Route::middleware('permission:modulo.proteccion.registrar')->group(function () {
            Route::get('/casos', [RegistrarCasoController::class, 'index'])->name('caso.index');
            Route::get('/caso/{id}', [RegistrarCasoController::class, 'show'])->name('caso.show');
            Route::post('/caso/{id}/completo', [RegistrarCasoController::class, 'storeCompleto'])->name('caso.completo.store');
            Route::get('/caso/{id}/editar', [RegistrarCasoController::class, 'edit'])->name('caso.edit');
            Route::post('/caso/{id}/actualizar', [RegistrarCasoController::class, 'update'])->name('caso.update');
            Route::get('/caso/{id}/ver', [RegistrarCasoController::class, 'showDetalle'])->name('caso.detalle');
        });
    });
    
    //-----------------------------------------------------
    // RUTAS ESPECÍFICAS PARA CADA ROL
    //-----------------------------------------------------
    Route::middleware('role:responsable')->prefix('responsable')->name('responsable.')->group(function () {
        Route::get('/dashboard', fn() => view('pages.responsable.dashboard'))->name('dashboard');
    });

    Route::middleware('role:legal')->prefix('legal')->name('legal.')->group(function () {
        Route::get('/dashboard', fn() => view('pages.legal.dashboard'))->name('dashboard');
        // **RUTA AÑADIDA PARA SOLUCIONAR EL ERROR**
        Route::resource('gestionar-adulto-mayor', LegalAdultoMayorController::class);
    });

    Route::middleware('role:asistente-social')->prefix('asistente-social')->name('asistente-social.')->group(function () {
        Route::get('/dashboard', fn() => view('pages.asistente-social.dashboard'))->name('dashboard');
    });
});
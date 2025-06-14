<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GestionarUsuariosController;
use App\Http\Controllers\GestionarRolesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Legal\LegalController;
use App\Http\Controllers\AsistenteSocial\OrientacionController;
use App\Http\Controllers\Medico\EnfermeriaController;
use App\Http\Controllers\Medico\FisioterapiaController;
use App\Http\Controllers\Medico\KinesiologiaController;
use App\Http\Controllers\Medico\MedicoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Este archivo ha sido reestructurado para seguir las mejores prácticas,
| asegurar que todas las rutas estén definidas correctamente y eliminar
| errores de sintaxis y duplicados.
|
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

    // Rutas Generales y Redirección del Dashboard
    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile.show');

    Route::get('/dashboard', function () {
        $user = Auth::user();
        if (!$user->rol) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['ci' => 'No tienes un rol asignado.']);
        }
        $roleName = strtolower($user->rol->nombre_rol);
        switch ($roleName) {
            case 'admin': return redirect()->route('admin.dashboard');
            case 'responsable': return redirect()->route('responsable.dashboard');
            case 'legal': return redirect()->route('legal.dashboard');
            case 'asistente-social': return redirect()->route('asistente-social.dashboard');
            default:
                Auth::logout();
                return redirect()->route('login')->withErrors(['ci' => 'Rol no reconocido.']);
        }
    })->name('dashboard');

    //-----------------------------------------------------
    // GRUPO DE RUTAS DE ADMINISTRADOR
    //-----------------------------------------------------
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Gestión de Usuarios con la ruta personalizada toggleActivity
        Route::resource('gestionar-usuarios', GestionarUsuariosController::class);
        // === CORRECCIÓN AÑADIDA AQUÍ ===
        Route::patch('/gestionar-usuarios/{id}/toggle-activity', [GestionarUsuariosController::class, 'toggleActivity'])->name('gestionar-usuarios.toggleActivity');

        Route::resource('gestionar-roles', GestionarRolesController::class);

        // Rutas de registro de personal
        Route::get('/registrar-asistente-social', [AdminController::class, 'showRegisterAsistenteSocial'])->name('registrar-asistente-social');
        Route::post('/store-asistente-social', [AdminController::class, 'storeAsistenteSocial'])->name('store-asistente-social');
        Route::get('/registrar-usuario-legal', [AdminController::class, 'showRegisterLegal'])->name('registrar-usuario-legal');
        Route::post('/store-legal', [AdminController::class, 'storeUsuarioLegal'])->name('store-legal');
        Route::get('/registrar-responsable-salud', [AdminController::class, 'showRegisterResponsableSalud'])->name('registrar-responsable-salud');
        Route::post('/store-responsable-salud', [AdminController::class, 'storeResponsableSalud'])->name('store-responsable-salud');
        
        // Gestión de Adultos Mayores
        Route::prefix('gestionar-adultos-mayores')->name('gestionar-adultomayor.')->group(function () {
            Route::get('/', [AdminController::class, 'gestionarAdultoMayorIndex'])->name('index');
            Route::get('/crear', [AdminController::class, 'showRegisterAdultoMayor'])->name('create');
            Route::post('/', [AdminController::class, 'storeAdultoMayor'])->name('store');
            Route::get('/buscar', [AdminController::class, 'buscarAdultoMayor'])->name('buscar');
            Route::get('/{ci}/editar', [AdminController::class, 'editarAdultoMayor'])->name('editar');
            Route::put('/{ci}', [AdminController::class, 'actualizarAdultoMayor'])->name('actualizar');
            Route::delete('/{ci}', [AdminController::class, 'eliminarAdultoMayor'])->name('eliminar');
        });
    });

    //-----------------------------------------------------
    // GRUPO DE RUTAS DEL ROL LEGAL
    //-----------------------------------------------------
    Route::middleware(['auth', 'role:legal'])->prefix('legal')->name('legal.')->group(function () {
        Route::get('/dashboard', [LegalController::class, 'dashboard'])->name('dashboard');
        Route::resource('gestionar-adultomayor', LegalController::class, ['parameters' => ['gestionar-adultomayor' => 'ci'], 'names' => 'gestionar-adultomayor'])->except(['show']);
        Route::get('gestionar-adultomayor/buscar', [LegalController::class, 'adultoMayorBuscar'])->name('gestionar-adultomayor.buscar');
        Route::get('/proteccion', [LegalController::class, 'proteccionIndex'])->name('proteccion.index');
        Route::get('/proteccion/create', [LegalController::class, 'proteccionCreate'])->name('proteccion.create');
        Route::post('/proteccion', [LegalController::class, 'proteccionStore'])->name('proteccion.store');
        Route::get('/proteccion/{id}', [LegalController::class, 'proteccionShow'])->name('proteccion.show');
        Route::get('/proteccion/{id}/edit', [LegalController::class, 'proteccionEdit'])->name('proteccion.edit');
        Route::put('/proteccion/{id}', [LegalController::class, 'proteccionUpdate'])->name('proteccion.update');
        Route::delete('/proteccion/{id}', [LegalController::class, 'proteccionDestroy'])->name('proteccion.destroy');
        Route::get('/proteccion/reportes', [LegalController::class, 'proteccionReportes'])->name('proteccion.reportes');
    });

    // === INICIO DE LA CORRECCIÓN ===
    // Se ha eliminado el bloque de código duplicado y el '});' extra que causaban el error 'ParseError'.
    // === FIN DE LA CORRECCIÓN ===

    //-----------------------------------------------------
    // GRUPO DE RUTAS DEL ROL ASISTENTE SOCIAL
    //-----------------------------------------------------
    Route::prefix('asistente-social')->name('asistente-social.')->middleware('role:asistente-social')->group(function () {
        Route::get('/dashboard', fn() => view('pages.asistente-social.dashboard'))->name('dashboard');
        Route::prefix('orientacion')->name('orientacion.')->group(function () {
            Route::get('/registrar-ficha', [OrientacionController::class, 'create'])->name('registrar-ficha');
            Route::get('/reportes', [OrientacionController::class, 'reportes'])->name('reportes');
        });
    });

    //-----------------------------------------------------
    // GRUPO DE RUTAS DEL ROL RESPONSABLE DE SALUD
    //-----------------------------------------------------
    Route::prefix('responsable')->name('responsable.')->middleware('role:responsable')->group(function () {
        Route::get('/dashboard', fn() => view('pages.responsable.dashboard'))->name('dashboard');
        Route::middleware('especialidad:Enfermeria')->group(function () {
            // ... rutas de enfermería
        });
        Route::middleware('especialidad:Fisioterapia')->group(function () {
            // ... rutas de fisioterapia
        });
        Route::middleware('especialidad:Kinesiologia')->group(function () {
            // ... rutas de kinesiología
        });
    });

}); // <-- CIERRE FINAL DEL GRUPO PRINCIPAL 'auth'

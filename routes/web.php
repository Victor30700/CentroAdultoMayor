<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GestionarUsuariosController;
use App\Http\Controllers\GestionarRolesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Legal\LegalController;
use App\Http\Controllers\AsistenteSocial\OrientacionController;
// Asegúrate de que todos los controladores que uses estén importados
// use App\Http\Controllers\Medico\EnfermeriaController;
// use App\Http\Controllers\Medico\FisioterapiaController;
// use App\Http\Controllers\Medico\KinesiologiaController;
// use App\Http\Controllers\Medico\MedicoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


//==========================================================================
// RUTAS PARA INVITADOS (NO AUTENTICADOS)
//==========================================================================
Route::middleware('guest')->group(function () {
    // Redirige la raíz a la página de login
    Route::get('/', fn() => redirect()->route('login'));
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

//==========================================================================
// RUTAS PROTEGIDAS (REQUIEREN AUTENTICACIÓN)
//==========================================================================
Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile.show');

    // LA RUTA '/dashboard' HA SIDO ELIMINADA PARA ROMPER EL BUCLE DE REDIRECCIÓN

    //-----------------------------------------------------
    // GRUPO DE RUTAS DE ADMINISTRADOR
    //-----------------------------------------------------
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        Route::resource('gestionar-usuarios', GestionarUsuariosController::class);
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
    Route::prefix('legal')->name('legal.')->middleware('role:legal')->group(function () {
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
        // Agrega aquí las rutas específicas para cada especialidad cuando las tengas
    });

});

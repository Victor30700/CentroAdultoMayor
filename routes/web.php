<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GestionarUsuariosController;
use App\Http\Controllers\GestionarRolesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Legal\LegalController;
use App\Http\Controllers\AsistenteSocial\OrientacionController;
// Nota: Los controladores para Enfermeria, etc., deben ser creados si no existen.
// use App\Http\Controllers\EnfermeriaController;
// use App\Http\Controllers\FisioterapiaController;
// use App\Http\Controllers\KinesiologiaController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Rutas para Invitados (No Autenticados) ---
Route::middleware('guest')->group(function () {
    Route::get('/', fn() => redirect()->route('login'));
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// --- Rutas Protegidas (Requieren Autenticación) ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile.show');

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
        
        // Gestión de Adultos Mayores (CRUD completo para Admin)
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
    // GRUPO DE RUTAS DEL ROL LEGAL (Accesible por legal y admin)
    //-----------------------------------------------------
    Route::prefix('legal')->name('legal.')->middleware('role:legal,admin')->group(function () {
        Route::get('/dashboard', [LegalController::class, 'dashboard'])->name('dashboard');
        // Rutas de Protección
        Route::get('/proteccion', [LegalController::class, 'proteccionIndex'])->name('proteccion.index');
        Route::get('/proteccion/create', [LegalController::class, 'proteccionCreate'])->name('proteccion.create');
        Route::post('/proteccion', [LegalController::class, 'proteccionStore'])->name('proteccion.store');
        
        // ==================== CORRECCIÓN AQUÍ ====================
        // Se eliminó el parámetro {id} que no era necesario
        Route::get('/proteccion/reportes', [LegalController::class, 'proteccionReportes'])->name('proteccion.reportes');
        // =======================================================
    });

    //-----------------------------------------------------
    // GRUPO DE RUTAS DEL ROL ASISTENTE SOCIAL (Accesible por asistente-social y admin)
    //-----------------------------------------------------
    Route::prefix('asistente-social')->name('asistente-social.')->middleware('role:asistente-social,admin')->group(function () {
        Route::get('/dashboard', fn() => view('pages.asistente-social.dashboard'))->name('dashboard');
        Route::prefix('orientacion')->name('orientacion.')->group(function () {
            Route::get('/registrar-ficha', [OrientacionController::class, 'create'])->name('registrar-ficha');
            Route::get('/reportes', [OrientacionController::class, 'reportes'])->name('reportes');
        });
    });

    //-----------------------------------------------------
    // GRUPO DE RUTAS DEL ROL RESPONSABLE (Accesible por responsable y admin)
    //-----------------------------------------------------
    Route::prefix('responsable')->name('responsable.')->middleware('role:responsable,admin')->group(function () {
        Route::get('/dashboard', fn() => view('pages.responsable.dashboard'))->name('dashboard');
        
        // Rutas de Enfermería
        Route::prefix('enfermeria')->name('enfermeria.')->group(function () {
            Route::get('/servicios', function() { return "Página de Servicios de Enfermería"; })->name('servicios');
            Route::get('/historias', function() { return "Página de Historias Clínicas"; })->name('historias');
            Route::get('/reportes', function() { return "Página de Reportes de Enfermería"; })->name('reportes');
        });

        // Rutas de Fisioterapia
        Route::prefix('fisioterapia')->name('fisioterapia.')->group(function () {
            Route::get('/atencion', function() { return "Página de Atención de Fisioterapia"; })->name('atencion');
            Route::get('/reportes', function() { return "Página de Reportes de Fisioterapia"; })->name('reportes');
        });

        // Rutas de Kinesiología
        Route::prefix('kinesiologia')->name('kinesiologia.')->group(function () {
            Route::get('/atencion', function() { return "Página de Atención de Kinesiología"; })->name('atencion');
            Route::get('/reportes', function() { return "Página de Reportes de Kinesiología"; })->name('reportes');
        });
    });
});
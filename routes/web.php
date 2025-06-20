<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GestionarUsuariosController;
use App\Http\Controllers\GestionarRolesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Legal\LegalController;
// Se elimina la importación del controlador OrientacionController ya que no se usa.
// use App\Http\Controllers\AsistenteSocial\OrientacionController; 
// Nota: Si usas controladores específicos para responsables, descomenta o añádelos aquí.
// use App\Http\Controllers\EnfermeriaController;
// use App\Http\Controllers\FisioterapiaController;
// use App\Http\Controllers\KinesiologiaController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Rutas de Invitados (No Autenticados) ---
Route::middleware('guest')->group(function () {
    Route::get('/', fn() => redirect()->route('login'));
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// --- Rutas Protegidas (Requieren Autenticación) ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile.show');

    // --- DASHBOARDS PARA CADA ROL ---
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard')->middleware('role:admin');
    Route::get('/legal/dashboard', [LegalController::class, 'dashboard'])->name('legal.dashboard')->middleware('role:admin,legal');
    Route::get('/responsable/dashboard', fn() => view('pages.responsable.dashboard'))->name('responsable.dashboard')->middleware('role:admin,responsable');
    // Se elimina la ruta del dashboard del asistente social.

    // =========================================================================================
    // === INICIO DE LA REESTRUCTURACIÓN: RUTAS COMPARTIDAS Y ESPECÍFICAS POR ROL ===
    // =========================================================================================

    // --- GRUPO DE RUTAS DE GESTIÓN DE ADULTOS MAYORES (Accesible por admin y legal) ---
    // Se elimina 'asistente-social' del middleware de este grupo.
    Route::prefix('gestionar-adultos-mayores')->name('gestionar-adultomayor.')->middleware('role:admin,legal')->group(function () {
        // --- Rutas estáticas ---
        Route::get('/', [AdminController::class, 'gestionarAdultoMayorIndex'])->name('index');
        Route::get('/crear', [AdminController::class, 'showRegisterAdultoMayor'])->name('create');
        Route::get('/buscar', [AdminController::class, 'buscarAdultoMayor'])->name('buscar');
        Route::post('/', [AdminController::class, 'storeAdultoMayor'])->name('store');
        
        // --- Rutas dinámicas (con parámetros) ---
        Route::get('/{ci}/editar', [AdminController::class, 'editarAdultoMayor'])->name('editar');
        Route::put('/{ci}', [AdminController::class, 'actualizarAdultoMayor'])->name('actualizar');
        Route::delete('/{ci}', [AdminController::class, 'eliminarAdultoMayor'])->name('eliminar');
    });

    // --- GRUPO DE RUTAS SOLO PARA ADMINISTRADOR ---
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        // Gestión de usuarios y roles
        Route::resource('gestionar-usuarios', GestionarUsuariosController::class)->except(['show']);
        Route::patch('/gestionar-usuarios/{id}/toggle-activity', [GestionarUsuariosController::class, 'toggleActivity'])->name('gestionar-usuarios.toggleActivity');
        Route::resource('gestionar-roles', GestionarRolesController::class)->except(['show']);

        // Rutas de registro de personal (solo admin)
        // Se eliminan las rutas para registrar un nuevo asistente social.
        Route::get('/registrar-usuario-legal', [AdminController::class, 'showRegisterLegal'])->name('registrar-usuario-legal');
        Route::post('/store-legal', [AdminController::class, 'storeUsuarioLegal'])->name('store-legal');
        Route::get('/registrar-responsable-salud', [AdminController::class, 'showRegisterResponsableSalud'])->name('registrar-responsable-salud');
        Route::post('/store-responsable-salud', [AdminController::class, 'storeResponsableSalud'])->name('store-responsable-salud');
    });

    // --- GRUPO DE RUTAS PARA ROL LEGAL (y admin) ---
    Route::prefix('legal')->name('legal.')->middleware('role:admin,legal')->group(function () {
        // Rutas de Protección
        Route::get('/proteccion', [LegalController::class, 'proteccionIndex'])->name('proteccion.index');
        Route::get('/proteccion/create', [LegalController::class, 'proteccionCreate'])->name('proteccion.create');
        Route::post('/proteccion', [LegalController::class, 'proteccionStore'])->name('proteccion.store');
        Route::get('/proteccion/reportes', [LegalController::class, 'proteccionReportes'])->name('proteccion.reportes');
    });

    // Se elimina por completo el grupo de rutas para 'asistente-social'.

    // --- GRUPO DE RUTAS PARA RESPONSABLE (y admin) ---
    Route::prefix('responsable')->name('responsable.')->middleware('role:admin,responsable')->group(function () {
        // Rutas de Enfermería (protegidas por especialidad)
        Route::prefix('enfermeria')->name('enfermeria.')->middleware('especialidad:Enfermeria')->group(function () {
            Route::get('/servicios', function() { return "Página de Servicios de Enfermería"; })->name('servicios');
            Route::get('/historias', function() { return "Página de Historias Clínicas"; })->name('historias');
            Route::get('/reportes', function() { return "Página de Reportes de Enfermería"; })->name('reportes');
        });

        // Rutas de Fisioterapia (protegidas por especialidad)
        Route::prefix('fisioterapia')->name('fisioterapia.')->middleware('especialidad:Fisioterapia-Kinesiologia')->group(function () {
            Route::get('/atencion', function() { return "Página de Atención de Fisioterapia"; })->name('atencion');
            Route::get('/reportes', function() { return "Página de Reportes de Fisioterapia"; })->name('reportes');
        });

        // Rutas de Kinesiología (protegidas por especialidad)
        Route::prefix('kinesiologia')->name('kinesiologia.')->middleware('especialidad:Fisioterapia-Kinesiologia')->group(function () {
            Route::get('/atencion', function() { return "Página de Atención de Kinesiología"; })->name('atencion');
            Route::get('/reportes', function() { return "Página de Reportes de Kinesiología"; })->name('reportes');
        });
    });
});
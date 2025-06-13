<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GestionarUsuariosController;
use App\Http\Controllers\RegistrarCasoController;
use App\Http\Controllers\GestionarRolesController;
use App\Http\Controllers\ProfileController;

// Controladores específicos para cada rol
use App\Http\Controllers\Legal\GestionarAdultoMayorController as LegalAdultoMayorController;
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
        
        if (!$user->rol) {
            Log::warning("El usuario con CI {$user->ci} intentó acceder sin un rol asignado.");
            Auth::logout();
            return redirect()->route('login')->withErrors(['ci' => 'No tienes un rol asignado. Contacta al administrador.']);
        }

        $roleName = strtolower($user->rol->nombre_rol);

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
        
        // Gestión de Usuarios
        Route::resource('gestionar-usuarios', GestionarUsuariosController::class);
        Route::patch('/gestionar-usuarios/{id}/toggle-activity', [GestionarUsuariosController::class, 'toggleActivity'])->name('gestionar-usuarios.toggleActivity');

        // Gestión de Roles
        Route::resource('gestionar-roles', GestionarRolesController::class);

        // Gestión de Adultos Mayores
        Route::prefix('gestionar-adultos-mayores')->name('gestionar-adultomayor.')->group(function () {
            Route::get('/', [AdminController::class, 'gestionarAdultoMayorIndex'])->name('index');
            Route::get('/buscar', [AdminController::class, 'buscarAdultoMayor'])->name('buscar');
            Route::get('/{ci}/editar', [AdminController::class, 'editarAdultoMayor'])->name('editar');
            Route::put('/{ci}', [AdminController::class, 'actualizarAdultoMayor'])->name('actualizar');
            Route::delete('/{ci}', [AdminController::class, 'eliminarAdultoMayor'])->name('eliminar');
        });

        // === INICIO DE CORRECCIÓN: Rutas de Registro de Personal ===
        Route::get('/registrar-asistente-social', [AdminController::class, 'showRegisterAsistenteSocial'])->name('registrar-asistente-social');
        Route::post('/store-asistente-social', [AdminController::class, 'storeAsistenteSocial'])->name('store-asistente-social');

        Route::get('/registrar-usuario-legal', [AdminController::class, 'showRegisterLegal'])->name('registrar-usuario-legal');
        Route::post('/store-legal', [AdminController::class, 'storeUsuarioLegal'])->name('store-legal');
        
        Route::get('/registrar-responsable-salud', [AdminController::class, 'showRegisterResponsableSalud'])->name('registrar-responsable-salud');
        Route::post('/store-responsable-salud', [AdminController::class, 'storeResponsableSalud'])->name('store-responsable-salud');

        Route::get('/registrar-adulto-mayor', [AdminController::class, 'showRegisterAdultoMayor'])->name('registrar-adulto-mayor');
        Route::post('/store-adulto-mayor', [AdminController::class, 'storeAdultoMayor'])->name('store-adulto-mayor');
        // === FIN DE CORRECCIÓN ===

        // Módulo Protección (Casos)
        Route::prefix('proteccion')->name('proteccion.')->group(function () {
            Route::get('/casos', [RegistrarCasoController::class, 'index'])->name('caso.index');
            Route::get('/caso/registrar', [RegistrarCasoController::class, 'create'])->name('caso.create');
            Route::post('/caso', [RegistrarCasoController::class, 'store'])->name('caso.store');
            Route::get('/caso/{id}', [RegistrarCasoController::class, 'show'])->name('caso.show');
            Route::get('/caso/{id}/editar', [RegistrarCasoController::class, 'edit'])->name('caso.edit');
            Route::put('/caso/{id}', [RegistrarCasoController::class, 'update'])->name('caso.update');
            Route::delete('/caso/{id}', [RegistrarCasoController::class, 'destroy'])->name('caso.destroy');
            Route::get('/reportes', [RegistrarCasoController::class, 'reportes'])->name('reportes');
        });

        // Módulo Orientación (Rutas apuntan a controladores vacíos, se deben implementar)
        Route::prefix('orientacion')->name('orientacion.')->group(function () {
             Route::get('/registrar-ficha', [OrientacionController::class, 'create'])->name('registrar-ficha');
             Route::get('/reportes', [OrientacionController::class, 'reportes'])->name('reportes');
        });

        // Módulo Médico (Rutas apuntan a controladores vacíos, se deben implementar)
        Route::prefix('medico')->name('medico.')->group(function () {
            Route::get('/servicios', [MedicoController::class, 'servicios'])->name('servicios');
            Route::get('/historias-clinicas', [MedicoController::class, 'historiasClinicas'])->name('historias-clinicas');
            Route::get('/enfermeria', [EnfermeriaController::class, 'index'])->name('enfermeria.index');
            Route::get('/enfermeria/reportes', [EnfermeriaController::class, 'reportes'])->name('enfermeria.reportes');
            Route::get('/fisioterapia', [FisioterapiaController::class, 'index'])->name('fisioterapia.index');
            Route::get('/fisioterapia/reportes', [FisioterapiaController::class, 'reportes'])->name('fisioterapia.reportes');
            Route::get('/kinesiologia', [KinesiologiaController::class, 'index'])->name('kinesiologia.index');
            Route::get('/kinesiologia/reportes', [KinesiologiaController::class, 'reportes'])->name('kinesiologia.reportes');
        });
    });

    //-----------------------------------------------------
    // RUTAS DEL ROL LEGAL
    //-----------------------------------------------------
    Route::prefix('legal')->name('legal.')->middleware('role:legal')->group(function () {
        Route::get('/dashboard', fn() => view('pages.legal.dashboard'))->name('dashboard');
        Route::resource('gestionar-adulto-mayor', LegalAdultoMayorController::class);
        Route::prefix('proteccion')->name('proteccion.')->group(function () {
            Route::get('/registrar-caso', [RegistrarCasoController::class, 'create'])->name('registrar-caso');
            Route::post('/registrar-caso', [RegistrarCasoController::class, 'store']);
            Route::get('/reportes', [RegistrarCasoController::class, 'reportes'])->name('reportes');
        });
    });

    //-----------------------------------------------------
    // RUTAS DEL ROL ASISTENTE SOCIAL
    //-----------------------------------------------------
    Route::prefix('asistente-social')->name('asistente-social.')->middleware('role:asistente-social')->group(function () {
        Route::get('/dashboard', fn() => view('pages.asistente-social.dashboard'))->name('dashboard');
        // AQUI DEBES AÑADIR EL CONTROLADOR PARA GESTIONAR ADULTO MAYOR DESDE EL ASISTENTE SOCIAL
        // Route::resource('gestionar-adulto-mayor', AsistenteSocialAdultoMayorController::class);
        Route::prefix('orientacion')->name('orientacion.')->group(function () {
            Route::get('/registrar-ficha', [OrientacionController::class, 'create'])->name('registrar-ficha');
            Route::get('/reportes', [OrientacionController::class, 'reportes'])->name('reportes');
        });
    });

    //-----------------------------------------------------
    // RUTAS DEL ROL RESPONSABLE DE SALUD
    //-----------------------------------------------------
    Route::prefix('responsable')->name('responsable.')->middleware('role:responsable')->group(function () {
        Route::get('/dashboard', fn() => view('pages.responsable.dashboard'))->name('dashboard');
        Route::middleware('especialidad:Enfermeria')->group(function () {
            Route::get('/servicios', [MedicoController::class, 'servicios'])->name('servicios');
            Route::get('/historias-clinicas', [MedicoController::class, 'historiasClinicas'])->name('historias-clinicas');
            Route::get('/enfermeria', [EnfermeriaController::class, 'index'])->name('enfermeria.index');
            Route::get('/enfermeria/reportes', [EnfermeriaController::class, 'reportes'])->name('enfermeria.reportes');
        });
        Route::middleware('especialidad:Fisioterapia')->group(function () {
            Route::get('/fisioterapia', [FisioterapiaController::class, 'index'])->name('fisioterapia.index');
            Route::get('/fisioterapia/reportes', [FisioterapiaController::class, 'reportes'])->name('fisioterapia.reportes');
        });
        Route::middleware('especialidad:Kinesiologia')->group(function () {
            Route::get('/kinesiologia', [KinesiologiaController::class, 'index'])->name('kinesiologia.index');
            Route::get('/kinesiologia/reportes', [KinesiologiaController::class, 'reportes'])->name('kinesiologia.reportes');
        });
    });
});

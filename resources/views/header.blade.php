<!doctype html>
<html lang="es" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Centro Hospitalario del Adulto Mayor">
    <meta name="author" content="Helmer Fellman Mendoza Jurado">
    <meta name="keywords" content="admin, dashboard, bootstrap, laravel, panel de control, centro de salud">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/brand/alcaldiaicon.png') }}">

    <!-- TITULO -->
    <title>Centro Hospitalario del Adulto Mayor</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- ESTILOS CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/plugins.css') }}" rel="stylesheet">

    <!-- ICONOS CSS -->
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    
    <!-- DataTables -->
    <link href="{{ asset('assets/plugins/DataTables/datatables.min.css') }}" rel="stylesheet">
    
    <!-- SWITCHER CSS -->
    <link href="{{ asset('assets/switcher/css/switcher.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/switcher/demo.css') }}" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="app sidebar-mini ltr light-mode">

    <!-- GLOBAL-LOADER -->
    <div id="global-loader">
        <img src="{{ asset('assets/images/loader.svg') }}" class="loader-img" alt="Loader">
    </div>
    <!-- /GLOBAL-LOADER -->

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">

            @php
                $user = Auth::user();
                $rol = strtolower(optional($user->rol)->nombre_rol ?? 'admin');
                $especialidad = strtolower(optional($user->responsableDetails)->especialidad ?? '');
                
                $dashboardRoute = route('login'); // Fallback
                if (in_array($rol, ['admin', 'legal', 'asistente-social', 'responsable'])) {
                    $dashboardRoute = route($rol . '.dashboard');
                }
            @endphp

            <!-- app-Header -->
            <div class="app-header header sticky">
                <div class="container-fluid main-container">
                    <div class="d-flex">
                        <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0)"></a>
                        
                        <a class="logo-horizontal" href="{{ $dashboardRoute }}">
                            <img src="{{ asset('assets/images/brand/alcaldiaicon.png') }}" class="header-brand-img light-logo" alt="logo">
                            <img src="{{ asset('assets/images/brand/logo-alcaldia.png') }}" class="header-brand-img light-logo1" alt="logo">
                        </a>

                        <div class="d-flex order-lg-2 ms-auto header-right-icons">
                            <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon fe fe-more-vertical"></span>
                            </button>
                            <div class="navbar navbar-collapse responsive-navbar p-0">
                                <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                                    <div class="d-flex order-lg-2">
                                        <div class="d-flex">
                                            <a class="nav-link icon theme-layout nav-link-bg layout-setting">
                                                <span class="dark-layout"><i class="fe fe-moon"></i></span>
                                                <span class="light-layout"><i class="fe fe-sun"></i></span>
                                            </a>
                                        </div>
                                        <div class="dropdown d-flex">
                                            <a class="nav-link icon full-screen-link nav-link-bg">
                                                <i class="fe fe-minimize fullscreen-button"></i>
                                            </a>
                                        </div>
                                        <div class="dropdown d-flex profile-1">
                                            <a href="javascript:void(0)" data-bs-toggle="dropdown" class="nav-link leading-none d-flex" aria-expanded="false">
                                                <img src="{{ asset('assets/images/users/userdefault.svg') }}" alt="profile-user" class="avatar profile-user brround cover-image">
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                <div class="drop-heading">
                                                    <div class="text-center">
                                                        <h5 class="text-dark mb-0 fs-14 fw-semibold">{{ $user->name }}</h5>
                                                        <small class="text-muted">
                                                            {{ ucfirst(str_replace('_', ' ', $rol)) }}
                                                            @if($rol == 'responsable' && $especialidad)
                                                                ({{ ucfirst($especialidad) }})
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="dropdown-divider m-0"></div>
                                                <a class="dropdown-item" href="{{ route('profile.show') }}">
                                                    <i class="dropdown-icon fe fe-user"></i> Perfil
                                                </a>
                                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    <i class="dropdown-icon fe fe-alert-circle"></i> Cerrar Sesión
                                                </a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /app-Header -->

            <!--APP-SIDEBAR-->
            <div class="sticky">
                <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
                <div class="app-sidebar">
                    <div class="side-header">
                        <a class="header-brand1" href="{{ $dashboardRoute }}">
                            <img src="{{ asset('assets/images/brand/logo-alcaldia.png') }}" class="header-brand-img desktop-logo" alt="logo">
                            <img src="{{ asset('assets/images/brand/alcaldiaicon.png') }}" class="header-brand-img toggle-logo" alt="logo">
                            <img src="{{ asset('assets/images/brand/alcaldiaicon.png') }}" class="header-brand-img light-logo" alt="logo">
                            <img src="{{ asset('assets/images/brand/logo-alcaldia.png') }}" class="header-brand-img light-logo1" alt="logo">
                        </a>
                    </div>
                    <div class="main-sidemenu">
                        <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" /></svg></div>
                        
                        <ul class="side-menu">
                            <li class="sub-category"><h3>MENÚ PRINCIPAL</h3></li>
                            <li class="slide">
                                <a class="side-menu__item" href="{{ $dashboardRoute }}">
                                    <i class="side-menu__icon fe fe-home"></i><span class="side-menu__label">Inicio</span>
                                </a>
                            </li>
                            
                            {{-- MENÚS EXCLUSIVOS PARA ADMIN --}}
                            @if($rol == 'admin')
                                <li class="sub-category"><h3>Administración</h3></li>
                                <li class="slide">
                                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-user-plus"></i><span class="side-menu__label">Registrar Usuarios</span><i class="angle fe fe-chevron-right"></i></a>
                                    <ul class="slide-menu">
                                        <li><a href="{{ route('admin.registrar-asistente-social') }}" class="slide-item">Asistente Social</a></li>
                                        <li><a href="{{ route('admin.registrar-usuario-legal') }}" class="slide-item">Personal Legal</a></li>
                                        <li><a href="{{ route('admin.registrar-responsable-salud') }}" class="slide-item">Responsable</a></li>
                                        {{-- ===================== CORRECCIÓN 1 ===================== --}}
                                        {{-- La ruta para crear paciente ahora no tiene el prefijo 'admin.' --}}
                                        <li><a href="{{ route('gestionar-adultomayor.create') }}" class="slide-item">Paciente</a></li>
                                    </ul>
                                </li>
                                <li class="slide">
                                    <a class="side-menu__item" href="{{ route('admin.gestionar-usuarios.index') }}"><i class="side-menu__icon fe fe-users"></i><span class="side-menu__label">Gestionar Usuarios</span></a>
                                </li>
                                 <li class="slide">
                                    <a class="side-menu__item" href="{{ route('admin.gestionar-roles.index') }}"><i class="side-menu__icon fe fe-shield"></i><span class="side-menu__label">Gestionar Roles</span></a>
                                </li>
                            @endif

                            {{-- MENÚ COMPARTIDO: GESTIÓN ADULTO MAYOR --}}
                            @if(in_array($rol, ['admin', 'legal', 'asistente-social']))
                                <li class="sub-category"><h3>Pacientes</h3></li>
                                <li class="slide">
                                    {{-- ===================== CORRECCIÓN 2 ===================== --}}
                                    {{-- La ruta para ver pacientes ahora no tiene el prefijo 'admin.' --}}
                                    <a class="side-menu__item" href="{{ route('gestionar-adultomayor.index') }}"><i class="side-menu__icon fe fe-user-check"></i><span class="side-menu__label">Gestionar Adulto Mayor</span></a>
                                </li>
                            @endif
                            
                            {{-- MENÚ COMPARTIDO: MÓDULO PROTECCIÓN --}}
                            @if(in_array($rol, ['admin', 'legal']))
                                <li class="sub-category"><h3>Módulo Protección</h3></li>
                                <li class="slide">
                                    <a class="side-menu__item" href="{{ route('legal.proteccion.create') }}"><i class="side-menu__icon fe fe-file-plus"></i><span class="side-menu__label">Registrar Caso</span></a>
                                </li>
                                 <li class="slide">
                                    <a class="side-menu__item" href="{{ route('legal.proteccion.reportes') }}"><i class="side-menu__icon fe fe-file-text"></i><span class="side-menu__label">Reportes Protección</span></a>
                                </li>
                            @endif
                            
                            {{-- MENÚ COMPARTIDO: MÓDULO ORIENTACIÓN --}}
                            @if(in_array($rol, ['admin', 'asistente-social']))
                                <li class="sub-category"><h3>Módulo Orientación</h3></li>
                                <li class="slide">
                                    <a class="side-menu__item" href="{{ route('asistente-social.orientacion.registrar-ficha') }}"><i class="side-menu__icon fe fe-edit-2"></i><span class="side-menu__label">Registrar Ficha</span></a>
                                </li>
                                <li class="slide">
                                    <a class="side-menu__item" href="{{ route('asistente-social.orientacion.reportes') }}"><i class="side-menu__icon fe fe-bar-chart-2"></i><span class="side-menu__label">Reportes Orientación</span></a>
                                </li>
                            @endif

                            {{-- MENÚS PARA RESPONSABLE DE SALUD (Y ADMIN) --}}
                            @if(in_array($rol, ['admin', 'responsable']))
                                <li class="sub-category"><h3>Módulo Médico</h3></li>
                                
                                @if($rol == 'admin' || $especialidad == 'enfermeria')
                                    <li class="slide">
                                        <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-heart"></i><span class="side-menu__label">Enfermería</span><i class="angle fe fe-chevron-right"></i></a>
                                        <ul class="slide-menu">
                                            <li><a href="{{ route('responsable.enfermeria.servicios') }}" class="slide-item">Servicios</a></li>
                                            <li><a href="{{ route('responsable.enfermeria.historias') }}" class="slide-item">Historias Clínicas</a></li>
                                            <li><a href="{{ route('responsable.enfermeria.reportes') }}" class="slide-item">Reportes Enfermería</a></li>
                                        </ul>
                                    </li>
                                @endif

                                @if($rol == 'admin' || $especialidad == 'fisioterapia')
                                <li class="slide">
                                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-activity"></i><span class="side-menu__label">Fisioterapia</span><i class="angle fe fe-chevron-right"></i></a>
                                    <ul class="slide-menu">
                                        <li><a href="{{ route('responsable.fisioterapia.atencion') }}" class="slide-item">Atención</a></li>
                                        <li><a href="{{ route('responsable.fisioterapia.reportes') }}" class="slide-item">Reportes Fisioterapia</a></li>
                                    </ul>
                                </li>
                                @endif

                                @if($rol == 'admin' || $especialidad == 'kinesiologia')
                                <li class="slide">
                                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-wind"></i><span class="side-menu__label">Kinesiología</span><i class="angle fe fe-chevron-right"></i></a>
                                    <ul class="slide-menu">
                                        <li><a href="{{ route('responsable.kinesiologia.atencion') }}" class="slide-item">Atención</a></li>
                                        <li><a href="{{ route('responsable.kinesiologia.reportes') }}" class="slide-item">Reportes Kinesiología</a></li>
                                    </ul>
                                </li>
                                @endif
                            @endif
                        </ul>

                        <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" /></svg></div>
                    </div>
                </div>
            </div>
            <!--/APP-SIDEBAR-->
        </div>
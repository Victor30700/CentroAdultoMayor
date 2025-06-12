{{-- Este archivo ahora es un PARCIAL. No contiene <html>, <head>, o <body>. --}}
{{-- Solo contiene el código del header y del sidebar para los usuarios. --}}

<!-- =============================== APP-HEADER =============================== -->
<div class="app-header header sticky">
    <div class="container-fluid main-container">
        <div class="d-flex">
            <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0)"></a>
            <a class="logo-horizontal " href="{{ route('dashboard') }}">
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
                                <a href="javascript:void(0)" data-bs-toggle="dropdown" class="nav-link leading-none d-flex">
                                    <img src="{{ asset('assets/images/users/userdefault.svg') }}" alt="profile-user" class="avatar profile-user brround cover-image">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <div class="drop-heading">
                                        <div class="text-center">
                                            <h5 class="text-dark mb-0 fs-14 fw-semibold">{{ Auth::user()->name }}</h5>
                                            <small class="text-muted">{{ optional(Auth::user()->rol)->nombre_rol ?? 'Usuario' }}</small>
                                            @if(Auth::user()->especialidad)
                                            <br><small class="text-muted">{{ Auth::user()->especialidad }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="dropdown-divider m-0"></div>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="dropdown-icon fe fe-user"></i> Perfil
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                    <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="dropdown-icon fe fe-alert-circle"></i> Cerrar Sesión
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- =============================== FIN APP-HEADER =============================== -->

<!-- =============================== INICIO SIDEBAR =============================== -->
<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar">
        <div class="side-header">
            <a class="header-brand1" href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/images/brand/logo-alcaldia.png') }}" class="header-brand-img desktop-logo" alt="logo">
                <img src="{{ asset('assets/images/brand/alcaldiaicon.png') }}" class="header-brand-img toggle-logo" alt="logo">
                <img src="{{ asset('assets/images/brand/alcaldiaicon.png') }}" class="header-brand-img light-logo" alt="logo">
                <img src="{{ asset('assets/images/brand/logo-alcaldia.png') }}" class="header-brand-img light-logo1" alt="logo">
            </a>
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" /></svg></div>
            
            <!-- =========== INICIO DEL MENÚ LATERAL DINÁMICO =========== -->
            <ul class="side-menu">
                <li class="sub-category">
                    <h3>Menú Principal</h3>
                </li>
                <li class="slide">
                    <a href="{{ route('dashboard') }}" class="side-menu__item has-link" data-bs-toggle="slide">
                        <i class="side-menu__icon fe fe-home"></i>
                        <span class="side-menu__label">Panel Principal</span>
                    </a>
                </li>

                {{-- MENÚ PARA ROL LEGAL --}}
                @if(optional(Auth::user()->rol)->nombre_rol == 'LEGAL')
                    <li class="sub-category"><h3>Módulo Legal</h3></li>
                    <li class="slide">
                        <a href="{{ route('legal.gestionar-adulto-mayor.index') }}" class="side-menu__item has-link" data-bs-toggle="slide">
                            <i class="side-menu__icon fe fe-users"></i>
                            <span class="side-menu__label">Gestionar Adulto Mayor</span>
                        </a>
                    </li>
                    @can('modulo.proteccion.registrar')
                    <li class="slide">
                        <a href="{{ route('admin.caso.index') }}" class="side-menu__item" data-bs-toggle="slide">
                            <i class="side-menu__icon fe fe-file-plus"></i>
                            <span class="side-menu__label">Registrar Caso</span>
                        </a>
                    </li>
                    @endcan
                @endif

                {{-- MENÚ PARA ROL ASISTENTE SOCIAL --}}
                @if(optional(Auth::user()->rol)->nombre_rol == 'ASISTENTE_SOCIAL')
                    <li class="sub-category"><h3>Módulo Social</h3></li>
                    <li class="slide">
                        {{-- TODO: Crear la ruta para este enlace --}}
                        <a href="#" class="side-menu__item has-link" data-bs-toggle="slide">
                            <i class="side-menu__icon fe fe-users"></i>
                            <span class="side-menu__label">Gestionar Adulto Mayor</span>
                        </a>
                    </li>
                    <li class="slide">
                        {{-- TODO: Crear la ruta para este enlace --}}
                        <a href="#" class="side-menu__item" data-bs-toggle="slide">
                            <i class="side-menu__icon fe fe-clipboard"></i>
                            <span class="side-menu__label">Registrar Ficha</span>
                        </a>
                    </li>
                @endif

                {{-- MENÚ PARA ROL RESPONSABLE (MÉDICO) --}}
                @if(optional(Auth::user()->rol)->nombre_rol == 'RESPONSABLE')
                    <li class="sub-category"><h3>Módulo Médico</h3></li>
                    <li class="slide">
                        {{-- TODO: Crear la ruta para este enlace --}}
                        <a href="#" class="side-menu__item" data-bs-toggle="slide">
                            <i class="side-menu__icon fe fe-folder"></i>
                            <span class="side-menu__label">Historias Clínicas</span>
                        </a>
                    </li>
                    @if(Auth::user()->especialidad)
                        <li class="slide">
                            {{-- TODO: Crear la ruta para este enlace --}}
                            <a href="#" class="side-menu__item" data-bs-toggle="slide">
                                <i class="side-menu__icon fe fe-activity"></i>
                                <span class="side-menu__label">Servicios de {{ Auth::user()->especialidad }}</span>
                            </a>
                        </li>
                    @endif
                @endif
            </ul>
            <!-- =========== FIN DEL MENÚ LATERAL DINÁMICO =========== -->
            
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" /></svg></div>
        </div>
    </div>
</div>
<!-- =============================== FIN SIDEBAR =============================== -->

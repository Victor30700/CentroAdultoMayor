<!doctype html>
<html lang="es" dir="ltr">

<head>
    <!-- Meta data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Centro Hospitalario del Adulto Mayor">
    <meta name="author" content="Helmer Fellman Mendoza Jurado">
    <meta name="keywords" content="admin, dashboard, bootstrap, laravel, panel de control, centro de salud">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/brand/alcaldiaicon.png') }}" />

    <!-- TITLE -->
    <title>Centro Hospitalario del Adulto Mayor</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/plugins.css') }}" rel="stylesheet" />
    
    <!--- FONT-ICONS CSS -->
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" />

    <!-- C3 CHARTS CSS -->
    <link href="{{ asset('assets/plugins/charts-c3/c3-chart.css') }}" rel="stylesheet" />

    <!--- SWITCHER CSS -->
    <link href="{{ asset('assets/switcher/css/switcher.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/switcher/demo.css') }}" rel="stylesheet">

    {{-- Directiva para que las páginas hijas puedan añadir sus propios estilos si es necesario --}}
    @yield('styles')
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
            
            {{-- Se incluye el header y la barra lateral como un parcial --}}
            @include('header')

            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">
                    {{-- Aquí se inyectará el contenido de cada página específica --}}
                    @yield('content')
                </div>
            </div>
            <!--app-content close-->
        </div>

        <!-- FOOTER -->
        @include('footer')
        <!-- FOOTER END -->

    </div>

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    {{-- =============================================== --}}
    {{-- SCRIPTS (MANTENIENDO TODOS TUS ARCHIVOS) --}}
    {{-- =============================================== --}}
    <!-- JQUERY JS -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <!-- BOOTSTRAP BUNDLE JS (Incluye Popper.js y es esencial para los dropdowns) -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SPARKLINE JS-->
    <script src="{{ asset('assets/js/jquery.sparkline.min.js') }}"></script>
    <!-- CHART-CIRCLE JS-->
    <script src="{{ asset('assets/js/circle-progress.min.js') }}"></script>
    <!-- C3 CHART JS-->
    <script src="{{ asset('assets/plugins/charts-c3/d3.v5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/charts-c3/c3-chart.js') }}"></script>
    <!-- INPUT MASK JS-->
    <script src="{{ asset('assets/plugins/input-mask/jquery.mask.min.js') }}"></script>
    <!-- SIDE-MENU JS-->
    <script src="{{ asset('assets/plugins/sidemenu/sidemenu.js') }}"></script>
    <!-- SIDEBAR JS -->
    <script src="{{ asset('assets/plugins/sidebar/sidebar.js') }}"></script>
    <!-- Perfect SCROLLBAR JS-->
    <script src="{{ asset('assets/plugins/p-scroll/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/plugins/p-scroll/pscroll.js') }}"></script>
    <script src="{{ asset('assets/plugins/p-scroll/pscroll-1.js') }}"></script>
    <!-- STICKY JS -->
    <script src="{{ asset('assets/js/sticky.js') }}"></script>
    <!-- APEXCHART JS -->
    <script src="{{ asset('assets/js/apexcharts.js') }}"></script>
    <!-- INDEX JS -->
    <script src="{{ asset('assets/js/index.js') }}"></script>
    <!-- Color Theme js -->
    <script src="{{ asset('assets/js/themeColors.js') }}"></script>
    <!-- CUSTOM JS -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <!-- SWITCHER JS -->
    <script src="{{ asset('assets/switcher/js/switcher.js') }}"></script>
    <!-- DATA TABLE JS-->
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/js/table-data.js') }}"></script>
    
    {{-- Esta directiva inyectará scripts adicionales que cada vista hija defina --}}
    @yield('scripts')

</body>

</html>
```

**Paso 3: Convertir el Header en un Parcial**

Ahora, el cambio más importante. Tu archivo `header.blade.php` **DEBE** ser un fragmento de código, **sin** las etiquetas `<html>`, `<head>`, `<body>` ni `script`.

**Reemplaza todo el contenido** de `resources/views/header.blade.php` con este código:


```php
{{-- Este archivo es un parcial. No contiene la estructura principal del HTML. --}}

<!-- app-Header -->
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
                                <a href="javascript:void(0)" data-bs-toggle="dropdown" class="nav-link leading-none d-flex" aria-expanded="false">
                                    <img src="{{ asset('assets/images/users/userdefault.svg') }}" alt="profile-user" class="avatar profile-user brround cover-image">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <div class="drop-heading">
                                        <div class="text-center">
                                            <h5 class="text-dark mb-0 fs-14 fw-semibold">{{ Auth::user()->name }}</h5>
                                            <small class="text-muted">
                                                {{ ucfirst(Auth::user()->rol->nombre_rol ?? 'Usuario') }}
                                                @if(Auth::user()->rol->nombre_rol == 'responsable' && Auth::user()->persona)
                                                    ({{ Auth::user()->persona->area_especialidad }})
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <div class="dropdown-divider m-0"></div>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="dropdown-icon fe fe-user"></i> Perfil
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
            <a class="header-brand1" href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/images/brand/logo-alcaldia.png') }}" class="header-brand-img desktop-logo" alt="logo">
                <img src="{{ asset('assets/images/brand/alcaldiaicon.png') }}" class="header-brand-img toggle-logo" alt="logo">
                <img src="{{ asset('assets/images/brand/alcaldiaicon.png') }}" class="header-brand-img light-logo" alt="logo">
                <img src="{{ asset('assets/images/brand/logo-alcaldia.png') }}" class="header-brand-img light-logo1" alt="logo">
            </a>
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" /></svg></div>
            
            @php
                $user = Auth::user();
                $role = $user->rol ? strtolower($user->rol->nombre_rol) : '';
                $especialidad = $user->persona ? $user->persona->area_especialidad : '';
            @endphp

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
                
                {{-- Lógica para incluir el menú correcto según el rol --}}
                @if($role == 'admin')
                    @include('partials.menus.admin')
                @elseif($role == 'legal')
                    @include('partials.menus.legal')
                @elseif($role == 'asistente-social')
                    @include('partials.menus.asistente-social')
                @elseif($role == 'responsable')
                    @include('partials.menus.responsable')
                @endif
            </ul>

            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" /></svg></div>
        </div>
    </div>
</div>
<!--/APP-SIDEBAR-->
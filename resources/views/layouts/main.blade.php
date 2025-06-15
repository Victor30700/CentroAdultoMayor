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
    <title>Centro Hospitalario del Adulto Mayor - @yield('title', 'Dashboard')</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/plugins.css') }}" rel="stylesheet" />
    
    <!--- FONT-ICONS CSS -->
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" />
    
    <!-- DataTables -->
    <link href="{{ asset('assets/plugins/DataTables/datatables.min.css') }}" rel="stylesheet">
    
    <!--- SWITCHER CSS -->
    <link href="{{ asset('assets/switcher/css/switcher.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/switcher/demo.css') }}" rel="stylesheet">

    {{-- Directiva para que las páginas hijas puedan añadir sus propios estilos si es necesario --}}
    @stack('styles')
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
                    <div class="main-container container-fluid">
                         {{-- Aquí se inyectará el contenido de cada página específica --}}
                         @yield('content')
                    </div>
                </div>
            </div>
            <!--app-content close-->
        </div>

        @include('footer')

    </div>

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- JQUERY JS (Siempre primero) -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    
    <!-- BOOTSTRAP JS -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- PLUGINS ESENCIALES DE LA PLANTILLA -->
    <script src="{{ asset('assets/js/sticky.js') }}"></script>
    <script src="{{ asset('assets/plugins/p-scroll/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/plugins/sidemenu/sidemenu.js') }}"></script>
    <script src="{{ asset('assets/plugins/sidebar/sidebar.js') }}"></script>
    
    <!-- SELECT2 JS -->
    <script src="{{ asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    
    <!-- DataTables y sus extensiones -->
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/js/table-data.js') }}"></script>
    
    <!-- Sweet Alert -->
    <script src="{{ asset('assets/plugins/sweet-alert/sweetalert2.all.min.js') }}"></script>
    
    <!-- THEME-COLOR JS -->
    <script src="{{ asset('assets/js/themeColors.js') }}"></script>

    <!-- CUSTOM JS -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    
    <!-- SWITCHER JS -->
    <script src="{{ asset('assets/switcher/js/switcher.js') }}"></script>

    {{-- Directiva para que las páginas hijas puedan añadir sus propios scripts --}}
    @stack('scripts')

</body>
</html>
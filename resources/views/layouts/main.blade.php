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

    <!-- C3 CHARTS CSS (Comentado porque el archivo no existe en tu proyecto y causa un error 404) -->
    {{-- <link href="{{ asset('assets/plugins/charts-c3/c3-chart.css') }}" rel="stylesheet" /> --}}

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

        <!-- INCLUYE EL FOOTER (QUE AHORA SOLO TIENE HTML) -->
        @include('footer')

    </div>

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- {{-- ======================================================= --}}
    {{-- BLOQUE ÚNICO DE SCRIPTS (CENTRALIZADO Y ORDENADO) --}}
    {{-- ======================================================= --}} -->
    
    <!-- GRUPO 1: LIBRERÍAS FUNDAMENTALES -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- GRUPO 2: PLUGINS ESENCIALES DE LA PLANTILLA (DEPENDEN DEL GRUPO 1) -->
    <script src="{{ asset('assets/js/sticky.js') }}"></script>
    <script src="{{ asset('assets/plugins/p-scroll/perfect-scrollbar.js') }}"></script> <!-- <<-- CARGADO ANTES DE SER USADO -->
    <script src="{{ asset('assets/plugins/sidemenu/sidemenu.js') }}"></script> <!-- <<-- USA PerfectScrollbar -->
    <script src="{{ asset('assets/plugins/sidebar/sidebar.js') }}"></script>
    
    <!-- GRUPO 3: PLUGINS PARA FUNCIONALIDADES DIVERSAS (Formularios, Tablas, etc.) -->
    <script src="{{ asset('assets/plugins/select2/select2.full.min.js') }}"></script> <!-- <<-- AÑADIDO: NECESARIO PARA table-data.js -->
    <script src="{{ asset('assets/plugins/input-mask/jquery.mask.min.js') }}"></script>
    <!-- DataTables y sus extensiones -->
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>

    <!-- GRUPO 4: SCRIPTS DE GRÁFICOS (Pueden fallar si no hay canvas, pero no bloquearán otros scripts) -->
    <script src="{{ asset('assets/js/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('assets/js/circle-progress.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/charts-c3/d3.v5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/charts-c3/c3-chart.js') }}"></script>
    <script src="{{ asset('assets/js/apexcharts.js') }}"></script>
    
    <!-- GRUPO 5: SCRIPTS DE INICIALIZACIÓN Y TEMA (Se cargan al final) -->
    <script src="{{ asset('assets/js/table-data.js') }}"></script> <!-- <<-- USA DataTables y Select2 -->
    <script src="{{ asset('assets/js/index1.js') }}"></script> <!-- <<-- Script problemático, lo dejamos al final -->
    <script src="{{ asset('assets/js/themeColors.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/switcher/js/switcher.js') }}"></script>
    
    <!-- {{-- Esta directiva inyectará scripts adicionales que cada vista hija defina --}}
    @yield('scripts') -->

</body>
</html>

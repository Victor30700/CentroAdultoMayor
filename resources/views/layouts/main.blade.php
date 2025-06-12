<!doctype html>
<html lang="en" dir="ltr">
<head>
    {{-- metas, CSS, favicon, etc. --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Centro Hospitalario del Adulto Mayor</title>

    {{-- Bootstrap y tus estilos --}}
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    {{-- … resto de tus CSS … --}}
</head>
<body class="app sidebar-mini ltr light-mode">
    {{-- GLOBAL LOADER --}}
    <div id="global-loader">
        <img src="{{ asset('assets/images/loader.svg') }}" class="loader-img" alt="Loader">
    </div>

    <div class="page">
      <div class="page-main">

        {{-- ========== HEADER ========== --}}
        <div class="app-header header sticky">
          {{-- … tu código actual del header (logo, buscador, dropdown de usuario, logout, etc.) … --}}
        </div>
        {{-- /HEADER --}}

        {{-- ========== SIDEBAR ========== --}}
        <div class="sticky">
          {{-- … tu menú lateral … --}}
        </div>
        {{-- /SIDEBAR --}}

        {{-- ========== CONTENIDO PRINCIPAL ========== --}}
        <div class="main-content app-content mt-0">
          <div class="side-app">
            {{-- aquí inyectamos cada vista --}}
            @yield('content')
          </div>
        </div>
        {{-- /CONTENIDO --}}

      </div>
    </div>

    {{-- ========== SCRIPTS ========== --}}
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/DataTables/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    {{-- … resto de tus scripts … --}}
</body>
</html>

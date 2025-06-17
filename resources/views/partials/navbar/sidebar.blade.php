<!-- {{--
Ruta: resources/views/partials/navbar/sidebar.blade.php
Este archivo es el responsable de cargar el menú lateral correcto según el rol del usuario.
Se ha reestructurado para ser compatible con el diseño del menú.
--}} -->
<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
<div class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{ url('/') }}">
            {{-- Puedes poner tu logo aquí --}}
            <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img desktop-logo" alt="logo">
            <img src="{{ asset('assets/images/brand/logo-1.png') }}" class="header-brand-img toggle-logo" alt="logo">
            <img src="{{ asset('assets/images/brand/logo-2.png') }}" class="header-brand-img light-logo" alt="logo">
            <img src="{{ asset('assets/images/brand/logo-3.png') }}" class="header-brand-img light-logo1" alt="logo">
        </a>
        <!-- LOGO -->
    </div>
    <div class="main-sidemenu">
        <div class="slide-left" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"/></svg></div>
        
        <ul class="side-menu">
            
            @if (Auth::check())
                @php
                    // Lógica simplificada para obtener el nombre del rol.
                    // Asegúrate de que tu modelo User tenga una relación 'rol' o un atributo 'role_name'.
                    $roleName = strtolower(Auth::user()->rol->nombre_rol ?? 'default');
                @endphp

                @switch($roleName)
                    @case('admin')
                        @include('partials.menus.admin')
                        @break

                    @case('legal')
                        @include('partials.menus.legal')
                        @break

                    @case('asistente-social')
                        @include('partials.menus.asistente_social')
                        @break

                    @case('responsable')
                        @php
                            // Lógica para especialidades del rol responsable
                            $especialidad = Auth::user()->persona->area_especialidad ?? '';
                        @endphp

                        @if($especialidad === 'Enfermeria')
                            @include('partials.menus.responsable_enfermeria')
                        @elseif($especialidad === 'Fisioterapia')
                            @include('partials.menus.responsable_fisioterapia')
                        @endif
                        {{-- Agrega más 'elseif' para otras especialidades si es necesario --}}
                        @break

                    @default
                        @include('partials.menus.default')
                @endswitch
            @endif

        </ul>

        <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/></svg></div>
    </div>
</div>

{{-- resources/views/partials/navbar/sidebar.blade.php --}}

{{-- NUEVO: Se añade una variable para obtener la ruta del dashboard del usuario actual --}}
@php
    $user = Auth::user();
    $roleName = strtolower($user->role_name ?? optional($user->rol)->nombre_rol);
    $dashboardRoute = 'login'; // Ruta por defecto si algo falla

    switch ($roleName) {
        case 'admin':
            $dashboardRoute = route('admin.dashboard');
            break;
        case 'responsable':
            $dashboardRoute = route('responsable.dashboard');
            break;
        case 'legal':
            $dashboardRoute = route('legal.dashboard');
            break;
        case 'asistente-social':
            $dashboardRoute = route('asistente-social.dashboard');
            break;
    }
@endphp

<aside class="sidebar bg-dark text-white p-3" style="min-height: 100vh;">
    <h4 class="mb-4">Menú</h4>
    <ul class="nav flex-column">
        <li class="nav-item mb-2">
            {{-- MODIFICADO: El enlace de "Inicio" ahora es dinámico --}}
            <a class="nav-link text-white" href="{{ $dashboardRoute }}">Inicio</a>
        </li>

        {{-- MODIFICADO: Se usa el nombre del rol en lugar del ID para mayor claridad --}}
        @if($roleName == 'admin')
            @include('partials.menus.admin')
        @elseif($roleName == 'legal')
            @include('partials.menus.legal')
        @elseif($roleName == 'asistente-social')
            @include('partials.menus.asistente_social')
        @elseif($roleName == 'responsable')
            {{-- Aquí puedes incluir menús específicos para responsables si los tienes --}}
            @php
                $especialidad = Auth::user()->persona->area_especialidad ?? '';
            @endphp

            @if($especialidad === 'Enfermeria')
                @include('partials.menus.responsable_enfermeria')
            @elseif($especialidad === 'Fisioterapia')
                @include('partials.menus.responsable_fisioterapia')
            @endif
            {{-- Agrega más `elseif` para otras especialidades si es necesario --}}

        @else
             @include('partials.menus.default')
        @endif

        <li class="nav-item mt-4">
            <a class="nav-link text-white" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Cerrar sesión
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</aside>
{{-- resources/views/partials/navbar/sidebar.blade.php --}}
<aside class="sidebar bg-dark text-white p-3" style="min-height: 100vh;">
    <h4 class="mb-4">Menú</h4>
    <ul class="nav flex-column">
        <li class="nav-item mb-2">
            <a class="nav-link text-white" href="/dashboard">Inicio</a>
        </li>

        {{-- Admin --}}
        @if(Auth::user()->id_rol == 1)
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">Panel Admin</a>
            </li>
            <li class="nav-item mb-2">
                {{-- CORRECCIÓN AQUÍ: Se cambió 'admin.users' por 'admin.gestionar-usuarios.index' --}}
                <a class="nav-link text-white" href="{{ route('admin.gestionar-usuarios.index') }}">Usuarios</a>
            </li>
        @endif

        {{-- Responsable --}}
        @if(Auth::user()->id_rol == 2)
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="{{ route('responsable.dashboard') }}">Panel Responsable</a>
            </li>
        @endif

        {{-- Legal --}}
        @if(Auth::user()->id_rol == 3)
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="{{ route('legal.dashboard') }}">Panel Legal</a>
            </li>
        @endif

        {{-- Asistente Social --}}
        @if(Auth::user()->id_rol == 4)
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="{{ route('asistente-social.dashboard') }}">Panel Asistente Social</a>
            </li>
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
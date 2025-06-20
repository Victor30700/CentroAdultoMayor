<!-- {{--
Ruta del archivo: resources/views/partials/menus/admin.blade.php
Corrección: Se han ajustado los nombres de las rutas para que coincidan con el archivo web.php
--}} -->

<li class="slide">
    <a class="side-menu__item" href="{{ route('admin.dashboard') }}">
        <i class="side-menu__icon fe fe-home"></i>
        <span class="side-menu__label">Dashboard</span>
    </a>
</li>

<li class="side-menu-label1"><span class="name">Administración</span></li>

<li class="slide">
    <!-- {{-- CORREGIDO: de 'admin.gestionar.usuarios.index' a 'admin.gestionar-usuarios.index' --}} -->
    <a class="side-menu__item" href="{{ route('admin.gestionar-usuarios.index') }}">
        <i class="side-menu__icon fe fe-users"></i>
        <span class="side-menu__label">Gestionar Usuarios</span>
    </a>
</li>

<li class="slide">
    <!-- {{-- CORREGIDO: de 'admin.gestionar.roles.index' a 'admin.gestionar-roles.index' --}} -->
    <a class="side-menu__item" href="{{ route('admin.gestionar-roles.index') }}">
        <i class="side-menu__icon fe fe-shield"></i>
        <span class="side-menu__label">Gestionar Roles</span>
    </a>
</li>

<li class="slide">
    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
        <i class="side-menu__icon fe fe-user-plus"></i>
        <span class="side-menu__label">Registrar Personal</span>
        <i class="angle fe fe-chevron-right"></i>
    </a>
    <ul class="slide-menu">
        <li class="side-menu-label1"><a href="javascript:void(0)">Registrar Personal</a></li>
        <!-- {{-- CORREGIDO: Rutas de registro --}} -->
        <!-- Se elimina el enlace para registrar Asistente Social -->
        <li><a href="{{ route('admin.registrar-usuario-legal') }}" class="slide-item">Área Legal</a></li>
        <li><a href="{{ route('admin.registrar-responsable-salud') }}" class="slide-item">Responsable de Salud</a></li>
    </ul>
</li>

<li class="side-menu-label1"><span class="name">Pacientes</span></li>

<li class="slide">
     <!-- {{-- CORREGIDO: Se añadió la ruta correcta --}} -->
    <a class="side-menu__item" href="{{ route('admin.gestionar-adultomayor.index') }}">
        <i class="side-menu__icon fe fe-user-check"></i>
        <span class="side-menu__label">Gestionar Adulto Mayor</span>
    </a>
</li>
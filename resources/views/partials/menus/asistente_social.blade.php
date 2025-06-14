{{--
Ruta: resources/views/partials/menus/asistente-social.blade.php
Menú específico para usuarios con rol 'asistente-social'
Acceso a: Gestionar Adulto Mayor, Módulo Orientación (Registrar Ficha, Reportes Orientación)
--}}

<li class="slide">
    <a class="side-menu__item" href="{{ route('asistente-social.dashboard') }}">
        <i class="side-menu__icon fe fe-home"></i>
        <span class="side-menu__label">Dashboard</span>
    </a>
</li>

{{-- Gestionar Adulto Mayor --}}
<li class="sub-category">
    <h3>Gestión de Adultos Mayores</h3>
</li>

<li class="slide has-sub">
    <a href="javascript:void(0);" class="side-menu__item">
        <i class="side-menu__icon fe fe-users"></i>
        <span class="side-menu__label">Gestionar Adulto Mayor</span>
        <i class="angle fe fe-chevron-down"></i>
    </a>
    <ul class="slide-menu">
        <li class="side-menu-label1"><a href="javascript:void(0)">Adultos Mayores</a></li>
        <li><a href="{{ route('asistente-social.gestionar-adultomayor.index') }}" class="slide-item">Ver Adultos Mayores</a></li>
        <li><a href="{{ route('asistente-social.registrar-adulto-mayor') }}" class="slide-item">Registrar Adulto Mayor</a></li>
    </ul>
</li>

{{-- Módulo Orientación --}}
<li class="sub-category">
    <h3>Módulo Orientación</h3>
</li>

<li class="slide has-sub">
    <a href="javascript:void(0);" class="side-menu__item">
        <i class="side-menu__icon fe fe-compass"></i>
        <span class="side-menu__label">Orientación</span>
        <i class="angle fe fe-chevron-down"></i>
    </a>
    <ul class="slide-menu">
        <li class="side-menu-label1"><a href="javascript:void(0)">Fichas de Orientación</a></li>
        <li><a href="{{ route('asistente-social.orientacion.registrar-ficha') }}" class="slide-item">Registrar Ficha</a></li>
        <li><a href="{{ route('asistente-social.orientacion.reportes') }}" class="slide-item">Reportes Orientación</a></li>
    </ul>
</li>
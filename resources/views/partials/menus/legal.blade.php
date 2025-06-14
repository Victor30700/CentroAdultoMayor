{{--
Ruta: resources/views/partials/menus/legal.blade.php
Menú específico para usuarios con rol 'legal'
Acceso a: Gestionar Adulto Mayor, Módulo Protección (Registrar Caso, Reportes Protección)
--}}

<li class="slide">
    <a class="side-menu__item" href="{{ route('legal.dashboard') }}">
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
        <li><a href="{{ route('legal.gestionar-adultomayor.index') }}" class="slide-item">Ver Adultos Mayores</a></li>
        <li><a href="{{ route('legal.registrar-adulto-mayor') }}" class="slide-item">Registrar Adulto Mayor</a></li>
    </ul>
</li>

{{-- Módulo Protección --}}
<li class="sub-category">
    <h3>Módulo Protección</h3>
</li>

<li class="slide has-sub">
    <a href="javascript:void(0);" class="side-menu__item">
        <i class="side-menu__icon fe fe-shield"></i>
        <span class="side-menu__label">Protección</span>
        <i class="angle fe fe-chevron-down"></i>
    </a>
    <ul class="slide-menu">
        <li class="side-menu-label1"><a href="javascript:void(0)">Casos de Protección</a></li>
        <li><a href="{{ route('legal.proteccion.index') }}" class="slide-item">Ver Casos</a></li>
        <li><a href="{{ route('legal.proteccion.create') }}" class="slide-item">Registrar Caso</a></li>
        <li><a href="{{ route('legal.proteccion.reportes') }}" class="slide-item">Reportes</a></li>
    </ul>
</li>
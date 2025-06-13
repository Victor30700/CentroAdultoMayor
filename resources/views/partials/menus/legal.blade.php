<!-- {{--
Ruta: resources/views/partials/menus/legal.blade.php
--}} -->
<li class="slide">
    <a class="side-menu__item" href="{{ route('legal.dashboard') }}">
        <i class="side-menu__icon fe fe-home"></i>
        <span class="side-menu__label">Dashboard</span>
    </a>
</li>

<li class="sub-category">
    <h3>Módulo Protección</h3>
</li>

<li class="slide">
    <!-- {{-- CORREGIDO: La ruta ahora es 'legal.proteccion.index' --}} -->
    <a href="{{ route('legal.proteccion.index') }}" class="side-menu__item">
        <i class="side-menu__icon fe fe-file-text"></i>
        <span class="side-menu__label">Ver Casos</span>
    </a>
</li>

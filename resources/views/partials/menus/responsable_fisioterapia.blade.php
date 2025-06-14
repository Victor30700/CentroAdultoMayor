{{--
Ruta: resources/views/partials/menus/responsable_fisioterapia.blade.php
Menú dinámico para usuarios con rol 'responsable'.
El contenido cambia según la especialidad del usuario (Fisioterapia, Kinesiologia, etc.).
--}}

{{-- Enlace al Dashboard Principal (Común para todos los responsables) --}}
<li class="slide">
    <a class="side-menu__item" href="{{ route('responsable.dashboard') }}">
        <i class="side-menu__icon fe fe-home"></i>
        <span class="side-menu__label">Dashboard</span>
    </a>
</li>

{{-- Verificación de la especialidad del usuario para mostrar el menú correcto --}}

{{-- MENÚ PARA FISIOTERAPIA --}}
@if(Auth::user()->especialidad == 'Fisioterapia')
    <li class="sub-category">
        <h3>Módulo Médico - Fisioterapia</h3>
    </li>
    <li class="slide has-sub">
        <a href="javascript:void(0);" class="side-menu__item">
            <i class="side-menu__icon fe fe-activity"></i>
            <span class="side-menu__label">Gestión Fisioterapia</span>
            <i class="angle fe fe-chevron-right"></i>
        </a>
        <ul class="sub-menu">
            <li>
                <a class="sub-side-menu__item" href="{{ route('fisioterapia.index') }}">
                    <span class="sub-side-menu__label">Fisioterapia</span>
                </a>
            </li>
            <li>
                <a class="sub-side-menu__item" href="{{ route('reportes.fisioterapia') }}">
                    <span class="sub-side-menu__label">Reportes Fisioterapia</span>
                </a>
            </li>
        </ul>
    </li>
@endif

{{-- MENÚ PARA KINESIOLOGÍA --}}
@if(Auth::user()->especialidad == 'Kinesiologia')
    <li class="sub-category">
        <h3>Módulo Médico - Kinesiología</h3>
    </li>
    <li class="slide has-sub">
        <a href="javascript:void(0);" class="side-menu__item">
            <i class="side-menu__icon fe fe-wind"></i>
            <span class="side-menu__label">Gestión Kinesiología</span>
            <i class="angle fe fe-chevron-right"></i>
        </a>
        <ul class="sub-menu">
            <li>
                <a class="sub-side-menu__item" href="{{ route('kinesiologia.index') }}">
                    <span class="sub-side-menu__label">Kinesiología</span>
                </a>
            </li>
            <li>
                <a class="sub-side-menu__item" href="{{ route('reportes.kinesiologia') }}">
                    <span class="sub-side-menu__label">Reportes Kinesiología</span>
                </a>
            </li>
        </ul>
    </li>
@endif

{{-- Puedes agregar más bloques @if para otras especialidades aquí --}}
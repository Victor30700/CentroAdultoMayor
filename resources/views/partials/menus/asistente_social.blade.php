{{--
Ruta del archivo: resources/views/partials/menus/asistente_social.blade.php
--}}
<li class="slide">
    <a class="side-menu__item" href="{{ route('asistente-social.dashboard') }}">
        <i class="side-menu__icon fe fe-home"></i>
        <span class="side-menu__label">Dashboard</span>
    </a>
</li>
<li class="sub-category">
    <h3>Módulo Orientación</h3>
</li>
<li class="slide">
    <a href="#" class="side-menu__item">
        <i class="side-menu__icon fe fe-file-plus"></i>
        <span class="side-menu__label">Registrar Ficha</span>
    </a>
</li>


<!-- {{--
Ruta del archivo: resources/views/partials/menus/responsable.blade.php
--}} -->
<li class="slide">
    <a class="side-menu__item" href="{{ route('responsable.dashboard') }}">
        <i class="side-menu__icon fe fe-home"></i>
        <span class="side-menu__label">Dashboard</span>
    </a>
</li>
<li class="sub-category">
    <h3>Módulo Médico</h3>
</li>
@if(Auth::user()->persona)
    @switch(Auth::user()->persona->area_especialidad)
        @case('Enfermeria')
            <li class="slide"><a href="#" class="side-menu__item"><i class="side-menu__icon fe fe-plus-circle"></i><span class="side-menu__label">Enfermería</span></a></li>
            @break
        @case('Fisioterapia')
            <li class="slide"><a href="#" class="side-menu__item"><i class="side-menu__icon fe fe-activity"></i><span class="side-menu__label">Fisioterapia</span></a></li>
            @break
        @case('Kinesiologia')
            <li class="slide"><a href="#" class="side-menu__item"><i class="side-menu__icon fe fe-repeat"></i><span class="side-menu__label">Kinesiología</span></a></li>
            @break
    @endswitch
@endif
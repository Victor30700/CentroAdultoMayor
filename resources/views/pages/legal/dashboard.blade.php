@extends('layouts.main')

@section('content')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <h1 class="page-title">Panel de Control (Legal)</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard Legal</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- CONTENIDO DE LA PÁGINA -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bienvenido al Módulo Legal</h3>
                </div>
                <div class="card-body">
                    <p>Aquí podrá gestionar los casos de protección, registrar nuevos casos y consultar la información relevante de los adultos mayores.</p>
                    <p>Utilice el menú de la izquierda para navegar por las diferentes opciones disponibles para su rol.</p>
                    {{-- CORREGIDO: La ruta ahora es 'legal.proteccion.index' --}}
                    <a href="{{ route('legal.proteccion.index') }}" class="btn btn-primary">Ver Casos de Protección</a>
                </div>
            </div>
        </div>
    </div>
    <!-- FIN DEL CONTENIDO DE LA PÁGINA -->
@endsection

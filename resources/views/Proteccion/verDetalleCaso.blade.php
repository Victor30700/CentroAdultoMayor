@extends('layouts.app')
@include('header')

@section('content')
<div class="container">
    <h3 class="mb-4">Detalle del Caso</h3>

    @include('Proteccion.partials.datosAdulto')
    @include('Proteccion.partials.actividadLaboral')
    @include('Proteccion.partials.encargado')

    <a href="{{ route('admin.caso.index') }}" class="btn btn-secondary mt-4">Volver</a>
</div>
@endsection

@include('footer')

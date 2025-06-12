@extends('layouts.app')
@include('header')
@section('content')
<div class="container">
    <h3>Adultos Mayores Registrados</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre completo</th>
                <th>CI</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($adultos as $adulto)
            <tr>
                <td>{{ $adulto->persona->nombres }} {{ $adulto->persona->primer_apellido }}</td>
                <td>{{ $adulto->persona->ci }}</td>
                <td>
                    <a href="{{ route('admin.caso.show', $adulto->id_adulto) }}" class="btn btn-sm btn-primary">Registrar Caso</a>
                    <a href="{{ route('admin.caso.edit', $adulto->id_adulto) }}" class="btn btn-sm btn-warning">Editar</a>
                    <a href="{{ route('admin.caso.detalle', $adulto->id_adulto) }}" class="btn btn-sm btn-info">Ver Detalle</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@include('footer')

@extends('layouts.main')

@section('content')
<div class="container">
    <div class="main-container">

        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Dashboard del Área Legal</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard Legal</li>
                </ol>
            </div>
        </div>
        <!-- PAGE-HEADER END -->

        <!-- CONTENIDO ESPECÍFICO DEL ROL -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Bienvenido al Panel Legal</h5>
                        <p class="card-text">
                            Aquí puedes gestionar los casos y la documentación legal relacionada con los adultos mayores.
                        </p>
                        <!-- Aquí puedes añadir más widgets, enlaces o información relevante -->
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN CONTENIDO ESPECÍFICO -->

    </div>
</div>
@endsection
{{-- resources/views/Admin/gestionarRoles/index.blade.php --}}
@extends('layouts.main')

@section('styles')
    {{-- Estilos específicos para esta página --}}
    <link rel="stylesheet" href="{{ asset('css/gestionarRoles.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.5/datatables.min.css"/>
@endsection

@section('content')
<div class="page">
    <div class="page-main">
        <div class="main-content app-content mt-0">
            <div class="side-app">
                <div class="main-container container-fluid">

                    <div class="page-header">
                        <h1 class="page-title">Gestionar Roles</h1>
                        <div>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Gestionar Roles</li>
                            </ol>
                        </div>
                    </div>

                    {{-- Mensajes de alerta de sesión --}}
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            <strong>¡Éxito!</strong>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            <strong>¡Error!</strong>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        {{-- Este botón ahora se mostrará correctamente --}}
                                        @can('roles.create')
                                            <a href="{{ route('admin.gestionar-roles.create') }}" class="btn btn-light btn-sm me-3">
                                                <i data-feather="plus-circle" class="me-1"></i>Agregar Rol
                                            </a>
                                        @endcan
                                        <h3 class="card-title text-white mb-0">Listado de Roles del Sistema</h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="rolesTable" class="table table-bordered table-striped w-100">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nombre del Rol</th>
                                                    <th>Descripción</th>
                                                    <th>Permisos</th>
                                                    <th>Usuarios</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($roles as $rol)
                                                    <tr>
                                                        <td><strong>{{ $rol->id_rol }}</strong></td>
                                                        <td>{{ $rol->nombre_rol }}</td>
                                                        <td>
                                                            <span title="{{ $rol->descripcion }}">
                                                                {{ Str::limit($rol->descripcion, 50) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info">{{ $rol->permissions_count }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-secondary">{{ $rol->users_count }}</span>
                                                        </td>
                                                        <td>
                                                            @if ($rol->active)
                                                                <span class="badge bg-success">Activo</span>
                                                            @else
                                                                <span class="badge bg-danger">Inactivo</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="btn-group" role="group">
                                                                @can('roles.edit')
                                                                    <a href="{{ route('admin.gestionar-roles.edit', $rol->id_rol) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Editar">
                                                                        <i class="fe fe-edit"></i>
                                                                    </a>
                                                                @endcan
                                                                @can('roles.delete')
                                                                    @if (!in_array(strtolower($rol->nombre_rol), ['admin', 'administrador']))
                                                                        <form action="{{ route('admin.gestionar-roles.destroy', $rol->id_rol) }}" method="POST" class="d-inline" onsubmit="return showCustomConfirm(event, '¿Está seguro de que desea eliminar este rol? Esta acción no se puede deshacer.', this)">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Eliminar Rol">
                                                                                <i class="fe fe-trash-2"></i>
                                                                            </button>
                                                                        </form>
                                                                    @else
                                                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" title="No se puede eliminar este rol" disabled>
                                                                            <i class="fe fe-trash-2"></i>
                                                                        </button>
                                                                    @endif
                                                                @endcan
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center text-muted">
                                                            <i class="fe fe-inbox"></i><br>
                                                            No hay roles registrados
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de Confirmación Personalizado --}}
<div class="custom-modal-overlay" id="customConfirmModalOverlay">
    <div class="custom-modal">
        <div class="custom-modal-header">
            <h5 class="modal-title">Confirmación</h5>
            <button type="button" class="btn-close" onclick="hideCustomConfirm()"></button>
        </div>
        <div class="custom-modal-body">
            <p id="customConfirmMessage"></p>
        </div>
        <div class="custom-modal-footer">
            <button type="button" class="btn btn-secondary" onclick="hideCustomConfirm()">Cancelar</button>
            <button type="button" class="btn btn-danger" id="customConfirmBtn">Confirmar</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.5/datatables.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        if (typeof $().DataTable === 'function') {
            $('#rolesTable').DataTable({
                language: {
                    // [MEJORA] Se utiliza el archivo de traducción local para evitar errores de CORS.
                    url: '{{ asset('assets/translates/Spanish.json') }}'
                },
                responsive: true,
                order: [[0, 'asc']],
                dom: 'lfrtip',
                columnDefs: [
                    { targets: [3, 4, 5, 6], orderable: false, searchable: false }
                ]
            });
        }
    });

    let currentConfirmForm = null;
    function showCustomConfirm(event, message, form) {
        event.preventDefault();
        currentConfirmForm = form;
        document.getElementById('customConfirmMessage').textContent = message;
        document.getElementById('customConfirmModalOverlay').classList.add('show');
    }
    function hideCustomConfirm() {
        document.getElementById('customConfirmModalOverlay').classList.remove('show');
        currentConfirmForm = null;
    }
    document.getElementById('customConfirmBtn').onclick = function() {
        if (currentConfirmForm) {
            currentConfirmForm.submit();
        }
    };
</script>
@endpush
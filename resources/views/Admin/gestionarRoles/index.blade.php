{{-- resources/views/Admin/gestionarRoles/index.blade.php --}}
@extends('layouts.main')

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/gestionarRoles.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    {{-- Cargamos DataTables --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
</head>

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
                        <div class="alert alert-error" role="alert">
                            <strong>¡Error!</strong>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h3 class="card-title text-white">Listado de Roles del Sistema</h3>
                                    <div class="card-options">
                                        @can('roles.create')
                                            <a href="{{ route('admin.gestionar-roles.create') }}" class="btn btn-white btn-sm">
                                                <i data-feather="plus-circle"></i> Agregar Nuevo Rol
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="rolesTable" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nombre del Rol</th>
                                                    <th>Descripción</th>
                                                    <th>Permisos Asignados</th>
                                                    <th>Usuarios con este Rol</th>
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
                                                                {{-- Botón para editar rol --}}
                                                                @can('roles.edit')
                                                                    <a href="{{ route('admin.gestionar-roles.edit', $rol->id_rol) }}" 
                                                                       class="btn btn-sm btn-info" 
                                                                       data-bs-toggle="tooltip" 
                                                                       title="Editar">
                                                                        <i class="fe fe-edit"></i>
                                                                    </a>
                                                                @endcan

                                                                {{-- Formulario para eliminar rol --}}
                                                                @can('roles.delete')
                                                                    @if (strtolower($rol->nombre_rol) !== 'admin' && strtolower($rol->nombre_rol) !== 'administrador')
                                                                        <form action="{{ route('admin.gestionar-roles.destroy', $rol->id_rol) }}" 
                                                                              method="POST" 
                                                                              class="d-inline"
                                                                              onsubmit="return showCustomConfirm(event, '¿Está seguro de eliminar este rol? Esta acción no se puede deshacer.', this)">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" 
                                                                                    class="btn btn-sm btn-danger" 
                                                                                    data-bs-toggle="tooltip" 
                                                                                    title="Eliminar Rol">
                                                                                <i class="fe fe-trash-2"></i>
                                                                            </button>
                                                                        </form>
                                                                    @else
                                                                        <button type="button" 
                                                                                class="btn btn-sm btn-secondary" 
                                                                                data-bs-toggle="tooltip" 
                                                                                title="No se puede eliminar este rol" 
                                                                                disabled>
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
                                                            <i class="fe fe-inbox"></i>
                                                            <br>
                                                            No hay roles registrados
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    {{-- Paginación si está disponible --}}
                                    @if(method_exists($roles, 'links') && $roles->hasPages())
                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $roles->links() }}
                                        </div>
                                    @endif
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
<script src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script>
    // Inicializar Feather Icons
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        // Inicialización de DataTables
        if (typeof $().DataTable === 'function') {
            $('#rolesTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                responsive: true,
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                pageLength: 25,
                order: [[0, 'asc']], // Ordenar por ID ascendente
                columnDefs: [
                    {
                        targets: [6], // Columna de acciones
                        orderable: false,
                        searchable: false
                    }
                ],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="fe fe-download"></i> Excel',
                        className: 'btn btn-success btn-sm'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fe fe-file-text"></i> PDF',
                        className: 'btn btn-danger btn-sm'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fe fe-printer"></i> Imprimir',
                        className: 'btn btn-info btn-sm'
                    }
                ]
            });
        }
    });

    // --- Funciones para el Modal de Confirmación Personalizado ---
    let currentConfirmForm = null;

    function showCustomConfirm(event, message, form) {
        event.preventDefault();
        currentConfirmForm = form;

        const overlay = document.getElementById('customConfirmModalOverlay');
        const msgElement = document.getElementById('customConfirmMessage');
        const confirmBtn = document.getElementById('customConfirmBtn');

        msgElement.textContent = message;
        
        confirmBtn.onclick = function() {
            if (currentConfirmForm) {
                currentConfirmForm.submit();
                hideCustomConfirm();
            }
        };

        overlay.classList.add('show');
        return false;
    }

    function hideCustomConfirm() {
        const overlay = document.getElementById('customConfirmModalOverlay');
        overlay.classList.remove('show');
        currentConfirmForm = null;
    }

    // --- Función para ver detalles del rol ---
    // Esta función ya no es necesaria ya que se removió el botón de ver detalles
    // function viewRole(roleId) { ... }
</script>
@endpush
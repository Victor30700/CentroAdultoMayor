{{-- views/Admin/gestionarRoles/create/index.blade.php --}}
@include('header')

{{-- Enlace al CSS personalizado para gestión de roles --}}
<link href="{{ asset('css/gestionarRolescss/createRoles.css') }}" rel="stylesheet">

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Crear Nuevo Rol</h1>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
            <p class="font-bold">Error de Validación</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
            <p class="font-bold">Error</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white shadow-xl rounded-lg p-6 md:p-8">
        <form action="{{ route('admin.gestionar-roles.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="nombre_rol" class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre del Rol <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="nombre_rol"
                    id="nombre_rol"
                    value="{{ old('nombre_rol') }}"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nombre_rol') border-red-500 @enderror"
                    placeholder="Ej: Editor"
                >
                @error('nombre_rol')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">
                    Descripción
                </label>
                <textarea
                    name="descripcion"
                    id="descripcion"
                    rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('descripcion') border-red-500 @enderror"
                    placeholder="Describe brevemente el propósito de este rol"
                >{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        name="active"
                        id="active"
                        value="1"
                        {{ old('active', true) ? 'checked' : '' }}
                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                    >
                    <label for="active" class="ml-2 block text-sm text-gray-900">Activo</label>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Asignar Permisos</h3>
                @if ($permissions->isEmpty())
                    <p class="text-sm text-gray-500">No hay permisos disponibles para asignar.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-h-96 overflow-y-auto p-4 border border-gray-200 rounded-md bg-gray-50">
                        @foreach ($permissions as $permission)
                            <div class="flex items-start p-2 hover:bg-gray-100 rounded-md transition duration-150 permission-item">
                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    id="permission_{{ $permission->id }}"
                                    value="{{ $permission->id }}"
                                    class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mt-0.5"
                                    {{ (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) ? 'checked' : '' }}
                                >
                                <label for="permission_{{ $permission->id }}" class="ml-3 text-sm text-gray-700 flex-1">
                                    <span class="font-semibold">{{ $permission->name }}</span>
                                    <p class="text-xs text-gray-500">{{ $permission->description }}</p>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endif

                @error('permissions')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('permissions.*')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-4">
                <a
                    href="{{ route('admin.gestionar-roles.index') }}"
                    class="button-cancel"
                >
                    Cancelar
                </a>
                <button type="submit" class="button-save">
                    <i class="fas fa-save mr-2"></i>Guardar Rol
                </button>
            </div>
        </form>
    </div>
</div>

@include('footer')

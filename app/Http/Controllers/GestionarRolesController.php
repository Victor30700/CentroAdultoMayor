<?php

namespace App\Http\Controllers; // Asegúrate que el namespace es correcto para tu estructura de proyecto

use App\Models\Rol; // Asegúrate que el namespace y nombre del modelo Rol es correcto
use App\Models\Permission; // Asegúrate que el namespace y nombre del modelo Permission es correcto
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GestionarRolesController extends Controller
{
    /**
     * Muestra una lista de los roles.
     */
    public function index()
    {
        $roles = Rol::withCount(['users', 'permissions'])
                        ->orderBy('nombre_rol', 'asc')
                        ->get();
        
        return view('Admin.gestionarRoles.index', compact('roles'));
    }

    /**
     * Muestra el formulario para crear un nuevo rol.
     */
    public function create()
    {
        $permissions = Permission::orderBy('name', 'asc')->get();
        
        return view('Admin.gestionarRoles.create.index', compact('permissions'));
    }

    /**
     * Almacena un nuevo rol en la base de datos.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_rol' => 'required|string|max:255|unique:rol,nombre_rol', // Asegúrate que 'rol' es el nombre correcto de tu tabla
            'descripcion' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id', // Asegúrate que 'permissions' es el nombre correcto de tu tabla de permisos y 'id' la PK
            'active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.gestionar-roles.create')
                            ->withErrors($validator)
                            ->withInput();
        }

        DB::beginTransaction();
        try {
            $rol = Rol::create([
                'nombre_rol' => $request->input('nombre_rol'),
                'descripcion' => $request->input('descripcion'),
                'active' => $request->boolean('active'), // boolean('active') es más robusto que input('active') para checkboxes
            ]);

            if ($request->has('permissions')) {
                $rol->permissions()->sync($request->input('permissions'));
            }

            DB::commit();
            $user = Auth::user();
            // Ajusta 'ci' al campo que uses para identificar al usuario en los logs si es diferente
            $userIdForLog = $user && isset($user->ci) ? $user->ci : ($user ? $user->id : 'Sistema'); 
            Log::info("Rol '{$rol->nombre_rol}' creado exitosamente por el usuario: " . $userIdForLog);

            return redirect()->route('admin.gestionar-roles.index')
                            ->with('success', 'Rol creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al crear el rol: " . $e->getMessage());
            return redirect()->route('admin.gestionar-roles.create')
                            ->with('error', 'Hubo un error al crear el rol. Por favor, inténtelo de nuevo.')
                            ->withInput();
        }
    }

    /**
     * Muestra el formulario para editar un rol existente.
     */
    public function edit($id) // Laravel puede hacer Route Model Binding: public function edit(Rol $rol)
    {
        $rol = Rol::findOrFail($id); // Si usas Route Model Binding, esta línea no es necesaria
        $permissions = Permission::orderBy('name', 'asc')->get();
        $rolePermissions = $rol->permissions->pluck('id')->toArray();

        return view('Admin.gestionarRoles.editar.edit', compact('rol', 'permissions', 'rolePermissions'));
    }

    /**
     * Actualiza el rol especificado en la base de datos.
     */
    public function update(Request $request, $id) // Laravel puede hacer Route Model Binding: public function update(Request $request, Rol $rol)
    {
        $rol = Rol::findOrFail($id); // Si usas Route Model Binding, esta línea no es necesaria

        $validator = Validator::make($request->all(), [
            // Asegúrate que 'id_rol' es la PK correcta de tu tabla 'rol'
            'nombre_rol' => 'required|string|max:255|unique:rol,nombre_rol,' . $rol->id_rol . ',id_rol', 
            'descripcion' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
            'active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.gestionar-roles.edit', $rol->id_rol)
                            ->withErrors($validator)
                            ->withInput();
        }

        DB::beginTransaction();
        try {
            $rol->update([
                'nombre_rol' => $request->input('nombre_rol'),
                'descripcion' => $request->input('descripcion'),
                'active' => $request->boolean('active'),
            ]);

            // Si se envían permisos vacíos (ningún checkbox marcado), se desasignan todos.
            $rol->permissions()->sync($request->input('permissions', [])); 

            DB::commit();
            $user = Auth::user();
            $userIdForLog = $user && isset($user->ci) ? $user->ci : ($user ? $user->id : 'Sistema');
            Log::info("Rol '{$rol->nombre_rol}' (ID: {$rol->id_rol}) actualizado exitosamente por el usuario: " . $userIdForLog);

            return redirect()->route('admin.gestionar-roles.index')
                            ->with('success', 'Rol actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al actualizar el rol (ID: {$rol->id_rol}): " . $e->getMessage());
            return redirect()->route('admin.gestionar-roles.edit', $rol->id_rol)
                            ->with('error', 'Hubo un error al actualizar el rol. Por favor, inténtelo de nuevo.')
                            ->withInput();
        }
    }

    /**
     * Elimina el rol especificado de la base de datos.
     */
    public function destroy($id) // Laravel puede hacer Route Model Binding: public function destroy(Rol $rol)
    {
        $rol = Rol::findOrFail($id); // Si usas Route Model Binding, esta línea no es necesaria

        if ($rol->users()->count() > 0) {
            Log::warning("Intento de eliminar el rol '{$rol->nombre_rol}' (ID: {$rol->id_rol}) que tiene usuarios asignados.");
            return redirect()->route('admin.gestionar-roles.index')
                                 ->with('error', 'No se puede eliminar el rol porque tiene usuarios asignados. Por favor, reasigne o elimine los usuarios primero.');
        }

        if (in_array(strtolower($rol->nombre_rol), ['admin', 'administrador'])) {
             Log::warning("Intento de eliminar el rol crítico '{$rol->nombre_rol}' (ID: {$rol->id_rol}).");
             return redirect()->route('admin.gestionar-roles.index')
                               ->with('error', 'No se puede eliminar este rol crítico del sistema.');
        }

        DB::beginTransaction();
        try {
            $rolNombre = $rol->nombre_rol;
            $rolId = $rol->id_rol;
            
            $rol->permissions()->detach(); // Desasigna todos los permisos antes de eliminar el rol
            $rol->delete();
            DB::commit();

            $user = Auth::user();
            $userIdForLog = $user && isset($user->ci) ? $user->ci : ($user ? $user->id : 'Sistema');
            Log::info("Rol '{$rolNombre}' (ID: {$rolId}) eliminado exitosamente por el usuario: " . $userIdForLog);

            return redirect()->route('admin.gestionar-roles.index')
                            ->with('success', 'Rol eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al eliminar el rol (ID: {$rol->id_rol}): " . $e->getMessage()); // Usar $rolId si $rol ya no existe
            return redirect()->route('admin.gestionar-roles.index')
                                 ->with('error', 'Hubo un error al eliminar el rol. Por favor, inténtelo de nuevo.');
        }
    }
}
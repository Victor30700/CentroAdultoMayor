<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Persona;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class GestionarUsuariosController extends Controller
{
    /**
     * Muestra una lista de todos los usuarios registrados.
     */
    public function index(Request $request)
    {
        $query = User::with(['persona', 'rol'])->orderBy('created_at', 'desc');

        // Lógica de búsqueda
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('ci', 'like', "%{$searchTerm}%")
                  ->orWhereHas('rol', function ($q) use ($searchTerm) {
                      $q->where('nombre_rol', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('persona', function ($q) use ($searchTerm) {
                      $q->where('nombres', 'like', "%{$searchTerm}%")
                        ->orWhere('primer_apellido', 'like', "%{$searchTerm}%")
                        ->orWhere('segundo_apellido', 'like', "%{$searchTerm}%");
                  });
            });
        }

        $users = $query->paginate(10);
        
        return view('Admin.gestionarUsers.index', compact('users'));
    }

    /**
     * Muestra el formulario para editar un usuario específico.
     */
    public function edit($id_usuario)
    {
        $user = User::with(['persona', 'rol'])->findOrFail($id_usuario);
        $roles = Rol::where('nombre_rol', '!=', 'superadmin')->get(); // Excluir superadmin de la lista

        // Determina si el rol del usuario es 'adulto_mayor' para deshabilitar la edición del rol
        $isAdultoMayorRole = optional($user->rol)->nombre_rol === 'adulto_mayor';

        return view('Admin.gestionarUsers.editar.edit', compact('user', 'roles', 'isAdultoMayorRole'));
    }

    /**
     * Actualiza los datos del usuario especificado en la base de datos.
     */
    public function update(Request $request, $id_usuario)
    {
        $user = User::with('persona')->findOrFail($id_usuario);
        $persona = $user->persona;

        // Reglas de validación base
        $rules = [
            'nombres' => 'required|string|max:255',
            'primer_apellido' => 'required|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'required|date|before_or_equal:today',
            'sexo' => 'required|string|in:M,F,O',
            'estado_civil' => 'required|string|in:casado,divorciado,soltero,otro',
            'domicilio' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'zona_comunidad' => 'nullable|string|max:100',
            'password' => 'nullable|string|min:8|confirmed',
        ];
        
        // No permitir cambiar el rol de 'adulto_mayor'
        if (optional($user->rol)->nombre_rol !== 'adulto_mayor') {
            $rules['id_rol'] = ['required', 'integer', Rule::exists('rol', 'id_rol')];
        }

        // Validación dinámica para area_especialidad según el rol
        $rolId = $request->input('id_rol', $user->id_rol); // Usa el rol enviado o el actual
        if ($rolId == 2) { // Rol Responsable de Salud
            $rules['area_especialidad'] = 'required|string|in:Enfermeria,Fisioterapia-Kinesiologia,otro';
        } elseif ($rolId == 3) { // Rol Legal
            $rules['area_especialidad'] = 'required|string|in:Asistente Social,Psicologia,Derecho';
        } else {
            $rules['area_especialidad'] = 'nullable|string'; // No requerido para otros roles
        }
        
        $validator = Validator::make($request->all(), $rules, [
            'nombres.required' => 'El nombre es obligatorio.',
            'primer_apellido.required' => 'El primer apellido es obligatorio.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'sexo.required' => 'El sexo es obligatorio.',
            'estado_civil.required' => 'El estado civil es obligatorio.',
            'domicilio.required' => 'El domicilio es obligatorio.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'id_rol.required' => 'El rol es obligatorio.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'area_especialidad.required' => 'El área de especialidad es obligatoria para el rol seleccionado.',
            'area_especialidad.in' => 'El valor de la especialidad no es válido para el rol seleccionado.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // Actualizar datos de Persona
            if ($persona) {
                $personaData = $request->only([
                    'nombres', 'primer_apellido', 'segundo_apellido', 'fecha_nacimiento',
                    'sexo', 'estado_civil', 'domicilio', 'telefono', 'zona_comunidad'
                ]);
                $personaData['edad'] = Carbon::parse($request->fecha_nacimiento)->age;
                
                // Actualizar area_especialidad si el rol lo requiere, de lo contrario, poner a null
                if ($rolId == 2 || $rolId == 3) {
                    $personaData['area_especialidad'] = $request->input('area_especialidad');
                } else {
                    $personaData['area_especialidad'] = null;
                }
                
                $persona->update($personaData);
            }

            // Actualizar datos de User
            $userData = [
                'name' => $request->nombres . ' ' . $request->primer_apellido,
            ];
            // Solo actualiza el rol si no es 'adulto_mayor'
            if (optional($user->rol)->nombre_rol !== 'adulto_mayor') {
                 $userData['id_rol'] = $request->id_rol;
            }
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);

            DB::commit();
            return redirect()->route('admin.gestionar-usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al actualizar usuario {$id_usuario}: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Ocurrió un error al actualizar el usuario.')->withInput();
        }
    }


    /**
     * Elimina un usuario específico de la base de datos (eliminación lógica).
     */
    public function destroy($id_usuario)
    {
        $user = User::findOrFail($id_usuario);

        if (Auth::id() == $id_usuario) {
            return response()->json(['success' => false, 'message' => 'No puedes eliminar tu propia cuenta.'], 403);
        }
        
        if (optional($user->rol)->nombre_rol === 'superadmin') {
            return response()->json(['success' => false, 'message' => 'No se puede eliminar al superadministrador.'], 403);
        }

        DB::beginTransaction();
        try {
            // Eliminar lógicamente al usuario
            $user->delete();
            // Eliminar lógicamente la persona asociada
            if ($user->persona) {
                $user->persona()->delete();
            }
            DB::commit();
            Log::info("Usuario CI: {$user->ci} y persona asociada eliminados (lógicamente) por el administrador.");
            return response()->json(['success' => true, 'message' => 'Usuario eliminado exitosamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al eliminar usuario {$id_usuario}: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al eliminar el usuario.'], 500);
        }
    }


    /**
     * Activa o desactiva la cuenta de un usuario.
     */
    public function toggleActivity($id_usuario)
    {
        $user = User::findOrFail($id_usuario);
        
        if (Auth::id() == $id_usuario) {
            return back()->with('error', 'No puedes desactivar tu propia cuenta.');
        }

        $user->active = !$user->active;
        $user->login_attempts = 0; // Resetear intentos al cambiar estado
        $user->temporary_lockout_until = null; // Quitar bloqueo temporal
        $user->save();

        $status = $user->active ? 'activado' : 'desactivado';
        return back()->with('success', "Usuario {$status} exitosamente.");
    }
}

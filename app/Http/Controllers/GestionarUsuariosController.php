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

class GestionarUsuariosController extends Controller
{
    /**
     * Muestra una lista de todos los usuarios registrados.
     */
    public function index()
    {
        $users = User::with(['persona', 'rol'])->orderBy('created_at', 'desc')->get();
        return view('Admin.gestionarUsers.index', compact('users'));
    }

    /**
     * Muestra el formulario para editar un usuario específico.
     * CORRECCIÓN: Cambiar el binding del modelo para usar la clave primaria correcta
     */
    public function edit($id)
    {
        // Buscar por la clave primaria correcta
        $user = User::with(['persona', 'rol'])->findOrFail($id);
        $roles = Rol::all();

        $isAdultoMayorRole = false;
        if ($user->rol && strtolower($user->rol->nombre_rol ?? '') === 'adulto_mayor') {
            $isAdultoMayorRole = true;
        }

        return view('Admin.gestionarUsers.editar.edit', compact('user', 'roles', 'isAdultoMayorRole'));
    }

    /**
     * Actualiza los datos del usuario especificado en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->load('persona', 'rol');
        $persona = $user->persona;

        $rules = [
            'nombres' => 'required|string|max:255',
            'primer_apellido' => 'required|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'required|date|before_or_equal:today',
            'sexo' => 'required|string|in:F,M,O',
            'estado_civil' => 'required|string|in:soltero,casado,viudo,divorciado,union_libre',
            'domicilio' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'zona_comunidad' => 'nullable|string|max:100',
            'id_rol' => ['required', 'integer', 'exists:rol,id_rol'],
            'password' => 'nullable|string|min:8|confirmed',
        ];

        $isAdultoMayorCurrentRole = false;
        if ($user->rol && strtolower($user->rol->nombre_rol ?? '') === 'adulto_mayor') {
            $isAdultoMayorCurrentRole = true;
        }

        if ($isAdultoMayorCurrentRole) {
            unset($rules['id_rol']);
        }

        $request->validate($rules, [
            'nombres.required' => 'El campo nombres es obligatorio.',
            'primer_apellido.required' => 'El campo primer apellido es obligatorio.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.before_or_equal' => 'La fecha de nacimiento no puede ser futura.',
            'sexo.required' => 'El campo sexo es obligatorio.',
            'estado_civil.required' => 'El estado civil es obligatorio.',
            'domicilio.required' => 'El domicilio es obligatorio.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'id_rol.required' => 'El rol es obligatorio.',
            'id_rol.exists' => 'El rol seleccionado no es válido.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la nueva contraseña no coincide.',
        ]);

        DB::beginTransaction();
        try {
            if ($persona) {
                $personaData = [
                    'nombres' => $request->nombres,
                    'primer_apellido' => $request->primer_apellido,
                    'segundo_apellido' => $request->segundo_apellido,
                    'fecha_nacimiento' => $request->fecha_nacimiento,
                    'edad' => Carbon::parse($request->fecha_nacimiento)->age,
                    'sexo' => $request->sexo,
                    'estado_civil' => $request->estado_civil,
                    'domicilio' => $request->domicilio,
                    'telefono' => $request->telefono,
                    'zona_comunidad' => $request->zona_comunidad,
                ];
                $persona->update($personaData);
            } else {
                Log::error("No se encontró la persona asociada al usuario CI: {$user->ci} durante la actualización.");
                DB::rollBack();
                return back()->with('error', 'No se encontró la información personal del usuario.');
            }

            $userData = [
                'name' => $request->nombres . ' ' . $request->primer_apellido,
            ];

            if (!$isAdultoMayorCurrentRole) {
                 $userData['id_rol'] = $request->id_rol;
            } else if ($request->filled('id_rol') && $request->id_rol != $user->id_rol) {
                Log::warning("Intento de cambio de rol para usuario 'adulto_mayor' CI: {$user->ci}. El cambio fue ignorado.");
            }

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            DB::commit();
            Log::info("Usuario actualizado exitosamente. CI: {$user->ci}");
            return redirect()->route('admin.gestionar-usuarios.index')->with('success', 'Usuario actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al actualizar usuario CI {$user->ci}: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Ocurrió un error al actualizar el usuario. Revise los logs para más detalles.')->withInput();
        }
    }

    /**
     * Elimina un usuario específico de la base de datos.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if (Auth::id() === $user->id_usuario) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        try {
            $ci = $user->ci;
            $user->delete(); 

            Log::info("Usuario eliminado exitosamente. CI: {$ci}");
            return redirect()->route('admin.gestionar-usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
        } catch (\Exception $e) {
            Log::error("Error al eliminar usuario CI {$user->ci}: " . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al eliminar el usuario.');
        }
    }

    /**
     * Activa o desactiva la cuenta de un usuario.
     */
    public function toggleActivity($id)
    {
        $user = User::findOrFail($id);
        
        if (Auth::id() === $user->id_usuario && $user->active) {
             return back()->with('error', 'No puedes desactivar tu propia cuenta de administrador.');
        }

        $user->active = !$user->active;

        if (!$user->active) {
            $user->temporary_lockout_until = null;
            $user->login_attempts = 0;
            $user->last_failed_login_at = null;
        }
        
        $user->save();

        $status = $user->active ? 'activado' : 'desactivado';
        Log::info("Estado del usuario CI {$user->ci} cambiado a {$status} por el administrador.");
        return back()->with('success', "Usuario {$status} exitosamente.");
    }
}
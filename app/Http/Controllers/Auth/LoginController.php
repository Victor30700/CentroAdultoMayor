<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'ci' => 'required|string',
            'password' => 'required|string',
        ], [
            'ci.required' => 'El carnet de identidad es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        $ci = $request->input('ci');
        $password = $request->input('password');

        // Buscar usuario por CI
        $user = User::where('ci', $ci)->first();

        if (!$user) {
            Log::warning("Intento de login con CI inexistente: {$ci}");
            return back()->withErrors([
                'ci' => 'Credenciales incorrectas.',
            ]);
        }

        // Verificar si el usuario puede hacer login
        if (!$user->canLogin()) {
            if (!$user->active && !$user->temporary_lockout_until) {
                // Usuario desactivado manualmente por admin
                Log::warning("Intento de login de usuario desactivado: {$ci}");
                return back()->withErrors([
                    'ci' => 'Su cuenta ha sido desactivada. Contacte al administrador.',
                ]);
            } elseif ($user->isTemporarilyLocked()) {
                // Usuario bloqueado temporalmente
                $minutesLeft = $user->getTimeUntilUnlock();
                Log::warning("Intento de login de usuario temporalmente bloqueado: {$ci}");
                return back()->withErrors([
                    'ci' => "Su cuenta está temporalmente bloqueada. Intente nuevamente en {$minutesLeft} minutos.",
                ]);
            }
        }

        // Verificar contraseña
        if (!Hash::check($password, $user->password)) {
            // Contraseña incorrecta - incrementar intentos
            $user->incrementLoginAttempts();
            
            $attemptsLeft = 3 - $user->login_attempts;
            
            Log::warning("Intento de login fallido para CI: {$ci}. Intentos: {$user->login_attempts}");
            
            if ($user->login_attempts >= 3) {
                return back()->withErrors([
                    'ci' => 'Ha excedido el número máximo de intentos. Su cuenta ha sido bloqueada por 10 minutos.',
                ]);
            } else {
                return back()->withErrors([
                    'ci' => "Credenciales incorrectas. Le quedan {$attemptsLeft} intentos.",
                ]);
            }
        }

        // Login exitoso
        $user->resetLoginAttempts();
        Auth::login($user);

        Log::info("Login exitoso para usuario: {$ci} - Rol: {$user->role_name}");

        // Redireccionar según el rol
        return $this->redirectUserBasedOnRole($user);
    }

    protected function redirectUserBasedOnRole($user)
    {
        $roleName = strtolower($user->role_name);
        
        switch ($roleName) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'responsable':
                return redirect()->route('responsable.dashboard');
            case 'legal':
                return redirect()->route('legal.dashboard');
            case 'asistente-social':
                return redirect()->route('asistente-social.dashboard');
            default:
                return redirect()->route('dashboard');
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        
        if ($user) {
            Log::info("Logout de usuario: {$user->ci}");
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Mostrar formulario de inicio de sesión
    public function showLoginForm()
    {
        return view('login');
    }

    // Procesar inicio de sesión
  // Procesar inicio de sesión
public function login(Request $request)
{
    // Validar datos de entrada
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    // Obtener el usuario por el email
    $user = User::where('email', $credentials['email'])->first();

    // Verificar si el usuario existe
    if ($user) {
        // Verificar si la contraseña coincide
        if (Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);

            if ($user->is_superadmin) {
                // Si el usuario es un superadmin, redirigir al dashboard de superadmin
                return redirect()->route('superadmin.dashboard');
            } else {
                // Si el usuario es regular, redirigir al dashboard regular
                return redirect()->route('user.dashboard');
            }
        } else {
            // Contraseña incorrecta
            return back()->withErrors(['email' => 'Credenciales inválidas'])
                         ->withInput($request->only('email'));
        }
    } else {
        // Usuario no encontrado
        return back()->withErrors(['email' => 'Credenciales inválidas'])
                     ->withInput($request->only('email'));
    }
}
}

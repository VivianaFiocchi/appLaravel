<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    // Mostrar formulario de restablecimiento de contraseña
    public function showResetForm()
    {
        return view('auth.reset-password');
    }
    
    // Procesar el restablecimiento de contraseña
    public function reset(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        // Buscar el usuario por su dirección de correo electrónico
        $user = User::where('email', $request->email)->first();

        // Verificar si el usuario existe
        if ($user) {
            // Actualizar la contraseña del usuario
            $user->password = Hash::make($request->password);
            $user->save();

            // Redirigir al usuario a la página de inicio de sesión con un mensaje de éxito
            return redirect()->route('login')->with('success', 'Contraseña restablecida correctamente. Por favor, inicia sesión con tu nueva contraseña.');
        } else {
            // Si el usuario no existe, redirigir con un mensaje de error
            return redirect()->route('login')->with('error', 'No se encontró ninguna cuenta asociada a esta dirección de correo electrónico.');
        }
    }
}

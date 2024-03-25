<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Asegúrate de importar el modelo User si aún no lo has hecho

class PasswordSetupController extends Controller
{
    /**
     * Mostrar el formulario para configurar la contraseña.
     *
     * @return \Illuminate\Http\Response
     */
    public function showPasswordSetupForm()
    {
        return view('auth.password-setup');
    }

    /**
     * Actualizar la contraseña del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Obtener el usuario autenticado
        $user = auth()->user();

        // Verificar si el usuario ya configuró su contraseña
        if ($user->password) {
            // Si ya tiene una contraseña configurada, redirigir a alguna página o acción
            return redirect()->route('home')->with('status', 'Ya has configurado tu contraseña.');
        }

        // Verificar si $user es un modelo Eloquent
        if ($user instanceof Model) {
            // $user es un modelo Eloquent, por lo que debería tener el método save()
            // Actualizar la contraseña del usuario
            $user->password = Hash::make($request->password);
            
            // Guardar los cambios en la base de datos
            $user->save();

            // Redirigir a alguna página o acción después de configurar la contraseña
            return redirect()->route('home')->with('status', 'Contraseña configurada correctamente.');
        } else {
            // $user no es un modelo Eloquent, maneja esta situación según sea necesario
            // Por ejemplo, lanza un error, registra un mensaje de error, etc.
            // En este caso, simplemente redirigir a alguna página con un mensaje de error
            return redirect()->route('home')->with('error', 'Error al actualizar la contraseña.');
        }
    }
}

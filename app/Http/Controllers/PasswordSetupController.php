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
    // Verificar si el usuario está autenticado correctamente
    if (!auth()->check()) {
        return redirect()->route('home')->with('error', 'Debes iniciar sesión para cambiar tu contraseña.');
    }

    // Validar la solicitud
    $request->validate([
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Obtener el usuario autenticado
    $user = auth()->user();

    // Verificar si el usuario es un objeto de tipo Eloquent
    if (!($user instanceof Model)) {
        return redirect()->back()->withInput()->with('error', 'El usuario no es válido.');
    }

    // Actualizar la contraseña del usuario
    $user->password = Hash::make($request->password);

    // Guardar los cambios en la base de datos
    try {
        $user->save();
        // Redirigir a alguna página o acción después de configurar la contraseña
        return redirect()->route('user.dashboard')->with('status', 'Contraseña configurada correctamente.');
    } catch (\Exception $e) {
        // Manejar el caso en que no se pudieron guardar los cambios
        return redirect()->back()->withInput()->with('error', 'Hubo un problema al actualizar la contraseña. Por favor, intenta de nuevo.');
    }
}


}

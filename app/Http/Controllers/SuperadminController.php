<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Models\Task;

class SuperadminController extends Controller
{
    public function dashboard()
    {
        // Obtener todas las tareas para mostrar en el dashboard
        $tasks = Task::all();
        return view('superadmin.dashboard', compact('tasks'));
    }

    public function showRegistrationForm()
    {
        return view('superadmin.registerNewUser');
    }
    public function registerNewUser(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Crear un nuevo usuario
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));

        $user->save();

        // Enviar correo electrónico de bienvenida
        Mail::to($user->email)->send(new WelcomeEmail($user));

        // Redirigir a alguna página o mostrar un mensaje de éxito
        return redirect()->route('superadmin.dashboard')->with('success', 'Usuario registrado exitosamente. Por favor, inicie sesión para comenzar.');
    }
}

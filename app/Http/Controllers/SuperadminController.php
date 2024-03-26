<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class SuperadminController extends Controller
{
    // Función para mostrar el dashboard de Superadmin
    public function dashboard()
    {
        $user = auth()->user();
        // Obtener todas las tareas
        $tasks = Task::all();
        // Obtener todos los usuarios
        $users = User::all();

        // Pasar las tareas a la vista
        return view('superadmin.dashboard', compact('user', 'tasks', 'users'));
    }

    // Función para mostrar el formulario de registro de nuevo usuario
    public function showRegistrationForm()
    {
        return view('superadmin.registerNewUser');
    }

    // Función para registrar un nuevo usuario
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

        // Redirigir a alguna página o mostrar un mensaje de éxito
        return redirect()->route('login')->with('success', 'Usuario registrado exitosamente. Por favor, inicie sesión para comenzar.');
    }

    // Función para mostrar el formulario de creación de una nueva tarea
    public function create()
    {
        // Obtener la lista de usuarios disponibles para asignar tareas
        $users = User::all();

        // Retornar la vista del formulario de creación de tarea con la lista de usuarios
        return view('superadmin.create', compact('users'));
    }

    // Función para almacenar una nueva tarea
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_user_id' => 'required|exists:users,id',
        ]);

        // Crear una nueva tarea
        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_user_id' => $request->assigned_user_id,
        ]);

        // Redirigir a alguna página o mostrar un mensaje de éxito
        return redirect()->route('superadmin.dashboard')->with('success', 'Tarea creada exitosamente.');
    }

    // Función para eliminar una tarea
    public function destroy($id)
    {
        // Buscar la tarea por su ID
        $task = Task::findOrFail($id);

        // Eliminar la tarea
        $task->delete();

        // Redirigir a alguna página o mostrar un mensaje de éxito
        return redirect()->route('superadmin.dashboard')->with('success', 'Tarea eliminada exitosamente.');
    }

    // Función para mostrar el formulario de edición de tarea
    public function edit($id)
    {
        // Buscar la tarea por su ID
        $task = Task::findOrFail($id);

        // Retornar la vista de edición con la tarea
        return view('superadmin.editTask', compact('task'));
    }
}

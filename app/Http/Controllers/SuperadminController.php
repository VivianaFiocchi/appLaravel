<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;
class SuperadminController extends Controller
{
    // Función para mostrar el dashboard de Superadmin
    public function dashboard()
{
    // Obtener el usuario autenticado
    $user = auth()->user();

    // Obtener todas las tareas asignadas al usuario actual y las asignadas a otros usuarios
    $tasks = Task::where('assigned_user_id', $user->id)
                 ->orWhere('assigned_user_id', '!=', $user->id)
                 ->get();

    // Ordenar las tareas para que primero aparezcan las asignadas al usuario actual
    $tasks = $tasks->sortByDesc(function ($task) use ($user) {
        return $task->assigned_user_id == $user->id ? 1 : 0;
    });

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
         // Enviar correo electrónico de bienvenida
        Mail::to($user->email)->send(new WelcomeEmail($user));
        // Redirigir a alguna página o mostrar un mensaje de éxito
        return redirect()->route('superadmin.dashboard')->with('success', 'Usuario registrado exitosamente. Por favor, inicie sesión para comenzar.');
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

        $users = User::all();

        // Retornar la vista de edición con la tarea
        return view('superadmin.edit', compact('task','users'));
    }

    // Función para actualizar una tarea
    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_user_id' => 'required|exists:users,id',
        ]);

        // Buscar la tarea por su ID
        $task = Task::findOrFail($id);

        // Actualizar los datos de la tarea
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_user_id' => $request->assigned_user_id,
        ]);

        // Redirigir a alguna página o mostrar un mensaje de éxito
        return redirect()->route('superadmin.dashboard')->with('success', 'Tarea actualizada exitosamente.');
        
    }
    public function addComment(Request $request, $taskId)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $task = Task::findOrFail($taskId);

        $comment = new Comment();
        $comment->content = $request->content;
        $comment->user_id = $user->id;

        $task->comments()->save($comment);

        return redirect()->back()->with('success', 'Comentario agregado exitosamente.');
    }
    public function uploadAttachment(Request $request, $taskId)
    {
        // Validar la solicitud
        $request->validate([
            'attachment' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // Ajusta los tipos y tamaños de archivo según tus necesidades
        ]);

        // Obtener la tarea
        $task = Task::findOrFail($taskId);

        // Procesar y almacenar el archivo adjunto
        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $attachmentPath = $request->file('attachment')->store('attachments'); // Almacena el archivo en la carpeta "attachments" dentro del almacenamiento de Laravel
            $task->attachment_path = $attachmentPath;
            $task->save();
        }

        return redirect()->back()->with('success', 'Archivo adjunto guardado exitosamente.');
    }
    public function deleteAttachment(Request $request, $taskId)
{
    // Obtener el ID de la tarea seleccionada
    $taskId = $request->input('task_id');

    // Verificar si se proporcionó un ID válido
    if (!$taskId) {
        return redirect()->back()->with('error', 'No se proporcionó un ID de tarea válido.');
    }

    // Obtener la tarea por su ID
    $task = Task::findOrFail($taskId);

    // Verificar permisos y eliminar el archivo adjunto si existe
    if (auth()->user()->is_superadmin || auth()->user()->id == $task->assigned_user_id || auth()->user()->id == $task->user_id) {
        if ($task->attachment_path) {
            // Eliminar el archivo adjunto
            Storage::delete($task->attachment_path);
            // Actualizar la ruta del archivo adjunto en la base de datos
            $task->update(['attachment_path' => ""]);
            return redirect()->back()->with('success', 'Archivo adjunto eliminado correctamente.');
        } else {
            return redirect()->back()->with('error', 'La tarea seleccionada no tiene ningún archivo adjunto.');
        }
    }

    return redirect()->back()->with('error', 'No tiene permiso para eliminar el archivo adjunto de esta tarea.');
}
public function updateTaskStatus(Request $request, $taskId)
{
    $task = Task::findOrFail($taskId);

    // Verificar si el usuario tiene permiso para actualizar el estado
    if (auth()->user()->is_superadmin || auth()->user()->id == $task->assigned_user_id) {
        $request->validate([
            'status' => 'required|in:Por realizar,En curso,Realizada',
        ]);

        $task->status = $request->status;
        $task->save();

        return redirect()->back()->with('success', 'Estado de la tarea actualizado exitosamente.');
    }

    return redirect()->back()->with('error', 'No tiene permiso para actualizar el estado de esta tarea.');
}
    


}
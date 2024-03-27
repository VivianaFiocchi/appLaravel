<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $userTasks = Task::where('assigned_user_id', $user->id)->get();

        // Verificar si hay usuarios que coincidan con los criterios de búsqueda
        $otherUsers = User::where('id', '!=', $user->id)
            ->where('is_superadmin', 0)
            ->pluck('name', 'id');

        $allTasks = [];
        foreach ($otherUsers as $userId => $userName) {
            $allTasks[$userId] = Task::where('assigned_user_id', $userId)->get();
        }

        // Verificar si la consulta devolvió resultados
        if ($otherUsers->isEmpty()) {
            // Si no hay usuarios, puedes asignar un array vacío a $otherUsers
            $otherUsers = [];
        }

        return view('user.dashboard', compact('user', 'userTasks', 'otherUsers', 'allTasks'));
    }

    public function viewTask($taskId)
    {
        $task = Task::findOrFail($taskId);
        $comments = $task->comments;
        return view('user.task', compact('task', 'comments'));
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
            // Eliminar el archivo adjunto existente si hay uno
            if ($task->attachment_path) {
                Storage::delete($task->attachment_path);
            }

            // Almacenar el nuevo archivo adjunto
            $attachmentPath = $request->file('attachment')->store('attachments');
            $task->attachment_path = $attachmentPath;
            $task->save();
        }

        return redirect()->back()->with('success', 'Archivo adjunto guardado exitosamente.');
    }

    public function deleteAttachment(Request $request, $taskId)
    {
        // Obtener la tarea
        $task = Task::findOrFail($taskId);

       // Verificar permisos y eliminar el archivo adjunto si existe
    if (auth()->user()->is_superadmin || auth()->user()->id == $task->assigned_user_id || auth()->user()->id == $task->user_id) {
        if ($task->attachment_path) {
            // Eliminar el archivo adjunto
            Storage::delete($task->attachment_path);
            // Actualizar la ruta del archivo adjunto en la base de datos
            $task->update(['attachment_path' => '']);
            return redirect()->back()->with('success', 'Archivo adjunto eliminado correctamente.');
        } else {
            return redirect()->back()->with('error', 'La tarea seleccionada no tiene ningún archivo adjunto.');
        }
    }

    return redirect()->back()->with('error', 'No tiene permiso para eliminar el archivo adjunto de esta tarea.');
}
}

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="container py-6">
        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Bienvenido {{ $user->name }}</h1>

        <div class="flex justify-end mb-4">
            <!-- Botón de signo + para desplegar las opciones -->
            <div class="relative inline-block text-left">
                <div>
                    <button type="button" id="options-menu-button" class="inline-flex items-center justify-center w-full rounded-md border border-gray-300 bg-blue-500 text-white px-4 py-2 font-medium hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
                        <i class="fas fa-plus mr-2"></i> <span class="text-xl font-bold">+</span>
                    </button>
                </div>

                <!-- Menú desplegable -->
                <div id="options-menu" class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5" role="menu" aria-orientation="vertical" aria-labelledby="options-menu-button">
                    <div class="py-1" role="none">
                        <!-- Opción para registrar un nuevo usuario -->
                        <a href="{{ route('superadmin.registro') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Registrar Nuevo Usuario</a>
                        <!-- Opción para crear una nueva tarea -->
                        <a href="{{ route('superadmin.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Crear Nueva Tarea</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tasks as $task)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="font-bold text-xl mb-2 text-gray-800">{{ $task->title }}</div>
                    <p class="text-gray-700 mb-4">Usuario Asignado: {{ $task->assignedUser->name }}</p>
                    <div class="text-gray-700 mb-4">Descripción: {{ $task->description }}</div>
                    <!-- Mostrar estado de la tarea -->
    <div class="text-gray-700 mb-4">Estado: {{ $task->status }}</div>
    <select name="status">
    <option value="Por realizar">Por realizar</option>
    <option value="En curso">En curso</option>
    <option value="Realizada">Realizada</option>
</select>
                    <!-- Mostrar archivo adjunto si existe -->
                    @if($task->attachment_path)
                    <div class="flex items-center mb-2">
                        <input type="checkbox" name="delete_attachments[]" value="{{ $task->id }}" class="mr-2">
                        <p class="text-gray-700">Archivo Adjunto: <a href="{{ Storage::url($task->attachment_path) }}" target="_blank">{{ basename($task->attachment_path) }}</a></p>
                    </div>
                    @endif
                </div>
                <div class="p-6 border-t border-gray-200">
                    <!-- Formulario para cargar archivos -->
                    <form action="{{ route('superadmin.uploadAttachment', ['taskId' => $task->id]) }}" method="POST" enctype="multipart/form-data" class="mb-4">
                        @csrf
                        <label for="attachment" class="block">Adjuntar Archivo:</label>
                        <input type="file" name="attachment" id="attachment" required>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2">Adjuntar Archivo</button>
                    </form>
                    <!-- Formulario para eliminar archivo adjunto -->
                    @if($task->attachment_path && (auth()->user()->id == $task->assigned_user_id || auth()->user()->id == $task->user_id || auth()->user()->is_superadmin))
                    <form action="{{ route('superadmin.deleteAttachment', ['taskId' => $task->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-4">Eliminar Archivo Adjunto</button>
                    </form>
                    @endif
                    <!-- Formulario para agregar comentarios -->
                    <form action="{{ route('superadmin.addComment', ['taskId' => $task->id]) }}" method="POST" class="mb-4">
                        @csrf
                        <label for="comment" class="block">Agregar Comentario:</label>
                        <textarea name="content" id="comment" rows="3" class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2">Agregar Comentario</button>
                    </form>
                    <!-- Comentarios -->
                    <div class="comments">
                        <h3 class="text-lg font-semibold mb-2">Comentarios</h3>
                        @if($task->comments->count() > 0)
                        <ul>
                            @foreach($task->comments as $comment)
                            <li>{{ $comment->content }}</li>
                            @endforeach
                        </ul>
                        @else
                        <p>No hay comentarios aún.</p>
                        @endif
                    </div>
                    <div class="flex justify-center">
                        <a href="{{ route('superadmin.edit', ['id' => $task->id]) }}" class="mr-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded btn">Modificar</a>
                        <form action="{{ route('superadmin.delete', $task->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded btn" onclick="return confirm('¿Estás seguro de que deseas eliminar esta tarea?')">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    // Obtener el botón y el menú desplegable
    const optionsMenuButton = document.getElementById('options-menu-button');
    const optionsMenu = document.getElementById('options-menu');

    // Agregar un event listener al botón para mostrar u ocultar el menú
    optionsMenuButton.addEventListener('click', () => {
        optionsMenu.classList.toggle('hidden');
    });
</script>
@endsection
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="container py-6">
        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Bienvenido {{ $user->name }}</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($userTasks as $task)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="font-bold text-xl mb-2 text-gray-800">{{ $task->title }}</div>
                        <p class="text-gray-700 mb-4">Usuario Asignado: {{ $task->assignedUser->name }}</p>
                        <div class="text-gray-700 mb-4">Descripción: {{ $task->description }}</div>

                        <!-- Mostrar archivo adjunto si existe -->
                        @if($task->attachment_path)
                            <div class="flex items-center mb-2">
                                <input type="checkbox" name="delete_attachments[]" value="{{ $task->id }}" class="mr-2">
                                <p class="text-gray-700">Archivo Adjunto: <a href="{{ Storage::url($task->attachment_path) }}" target="_blank">{{ basename($task->attachment_path) }}</a></p>
                            </div>
                            <!-- Formulario para eliminar archivo adjunto -->
                            <form action="{{ route('user.deleteAttachment', ['taskId' => $task->id]) }}" method="POST" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Eliminar Archivo Adjunto</button>
                            </form>
                        @endif

                        <!-- Formulario para cargar archivos -->
<form action="{{ route('user.uploadAttachment', ['taskId' => $task->id]) }}" method="POST" enctype="multipart/form-data" class="mt-4">
    @csrf
    <label for="attachment">Adjuntar Archivos:</label>
    <input type="file" name="attachments[]" id="attachment" multiple required>
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Adjuntar Archivos</button>
</form>

                        <!-- Formulario para agregar comentarios -->
                        <form action="{{ route('user.addComment', ['taskId' => $task->id]) }}" method="POST" class="mt-4">
                            @csrf
                            <textarea name="content" rows="3" cols="50" required placeholder="Escribe tu comentario aquí..."></textarea><br>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Agregar Comentario</button>
                        </form>

                        <!-- Comentarios -->
                        <div class="comments mt-4">
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
                    </div>
                    <div class="p-4 bg-gray-200 border-t border-gray-300">
                        <div class="flex justify-center">
                            <a href="{{ route('user.edit', ['id' => $task->id]) }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">Modificar</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Tareas de otros Usuarios -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4 text-center">Tareas de otros Usuarios</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 " >
                @foreach($otherUsers as $userId => $userName)

                    @foreach($allTasks[$userId] as $task)
                        <div class="bg-white rounded-lg shadow-lg p-6 cursor-pointer">
                            <div class="font-bold text-xl mb-2 text-gray-800">{{ $task->title }}</div>
                            <p class="text-gray-700 mb-4">Usuario Asignado: {{ $task->assignedUser->name }}</p>
                            <div class="text-gray-700 mb-4">Descripción: {{ $task->description }}</div>

                            <!-- Mostrar archivo adjunto si existe -->
                            @if($task->attachment_path)
                                <div class="flex items-center mb-2">
                                    <p class="text-gray-700">Archivo Adjunto: <a href="{{ Storage::url($task->attachment_path) }}" target="_blank">{{ basename($task->attachment_path) }}</a></p>
                                </div>
                            @endif
                            
                           <!-- Formulario para cargar archivos -->
<form action="{{ route('user.uploadAttachment', ['taskId' => $task->id]) }}" method="POST" enctype="multipart/form-data" class="mt-4">
    @csrf
    <label for="attachment">Adjuntar Archivos:</label>
    <input type="file" name="attachments[]" id="attachment" multiple required>
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Adjuntar Archivos</button>
</form>

                            <!-- Formulario para agregar comentarios -->
                            <form action="{{ route('user.addComment', ['taskId' => $task->id]) }}" method="POST" class="mt-4">
                            @csrf
    <textarea name="content" rows="3" cols="50" required placeholder="Escribe tu comentario aquí..."></textarea><br>
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Agregar Comentario</button>
</form>

<!-- Comentarios -->
<div class="comments mt-4">
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
</div>
</div>
@endforeach
</div>
@endforeach
</div>
</div>
</div>
</div>
<script>
    function toggleDescription(taskId, event) {
        if (event.target.classList.contains('btn-modify')) {
            return;
        }

        const description = document.querySelector(`#description-${taskId}`);
        description.classList.toggle('hidden');
    }
</script>
@endsection

<div class="bg-white rounded-lg shadow-lg p-6 cursor-pointer" onclick="toggleDescription('{{ $task->id }}', event)">
    <div class="font-bold text-xl mb-2 text-gray-800">{{ $task->title }}</div>
    <p class="text-gray-700 mb-4">Usuario Asignado: {{ $task->assignedUser->name }}</p>
    <div id="description-{{ $task->id }}" class="description hidden text-gray-700">{{ $task->description }}</div>

    @if($task->attachment_path)
    <p class="text-gray-700 mb-4">Archivo Adjunto: <a href="{{ Storage::url($task->attachment_path) }}" target="_blank">{{ basename($task->attachment_path) }}</a></p>
    @endif

    <form action="{{ route('user.uploadAttachment', ['taskId' => $task->id]) }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf
        <label for="attachment">Adjuntar Archivo:</label>
        <input type="file" name="attachment" id="attachment" required>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Adjuntar Archivo</button>
    </form>

    @if($task->attachment_path && (auth()->user()->id == $task->assigned_user_id || auth()->user()->id == $task->user_id || auth()->user()->is_superadmin))
    <form action="{{ route('user.deleteAttachment', ['taskId' => $task->id]) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-4">Eliminar Archivo Adjunto</button>
    </form>
    @endif

    <form action="{{ route('user.addComment', ['taskId' => $task->id]) }}" method="POST" class="mt-4">
        @csrf
        <textarea name="content" rows="3" cols="50" required placeholder="Escribe tu comentario aquí..."></textarea><br>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Agregar Comentario</button>
    </form>

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

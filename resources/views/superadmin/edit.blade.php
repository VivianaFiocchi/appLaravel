@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background-color: #f8f9fa;">
    <h1 class="mb-8 text-3xl font-bold text-center py-6" style="color: #343a40;">Editar Tarea</h1>

    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6">
            <form method="POST" action="{{ route('superadmin.update', ['id' => $task->id]) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="title" class="block text-sm font-bold text-gray-700">Título de la Tarea:</label>
                    <input type="text" id="title" name="title" value="{{ $task->title }}" class="mt-1 px-4 py-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-bold text-gray-700">Descripción:</label>
                    <textarea id="description" name="description" rows="4" class="mt-1 px-4 py-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>{{ $task->description }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="assigned_user_id" class="block text-sm font-bold text-gray-700">Usuario Asignado:</label>
                    <select id="assigned_user_id" name="assigned_user_id" class="mt-1 px-4 py-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $task->assigned_user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-center">
                    <button type="submit" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Actualizar Tarea</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

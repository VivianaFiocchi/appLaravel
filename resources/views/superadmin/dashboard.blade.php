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
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="font-bold text-xl mb-2 text-gray-800">{{ $task->title }}</div>
                <p class="text-gray-700 mb-4">Usuario Asignado: {{ $task->assignedUser->name }}</p>
                <div class="flex justify-center">
                    <a href="{{ route('superadmin.edit', ['id' => $task->id]) }}" class="mr-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded btn">Modificar</a>
                    <form action="{{ route('superadmin.delete', $task->id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded btn" onclick="return confirm('¿Estás seguro de que deseas eliminar esta tarea?')">Eliminar</button>
                    </form>
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
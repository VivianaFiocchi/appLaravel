@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background-color: #f8f9fa;">
    <h1 class="mb-8 text-3xl font-bold text-center py-6" style="color: #343a40;">Bienvenido {{ $user->name }}</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 px-4">
        @foreach($tasks as $task)
        <div class="bg-white rounded-lg shadow-lg p-6" style="border: 1px solid #dee2e6;">
            <div class="font-bold text-xl mb-2" style="color: #495057;">{{ $task->title }}</div>
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

    <div class="container">
        <h1 class="mb-4">Welcome to Superadmin Dashboard</h1>

        <div class="row mb-4">
            <div class="col">
                <!-- Botón para redirigir a la vista de registro -->
                <a href="{{ route('superadmin.registro') }}" class="btn btn-primary">Registrar Nuevo Usuario</a>
            </div>
            <div class="col text-right">
                <!-- Botón para redirigir a la vista de creación de tarea -->
                <a href="{{ route('superadmin.create') }}" class="btn btn-success">Crear Nueva Tarea</a>
            </div>
        </div>

        <div class="row">
            @foreach($tasks as $task)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $task->title }}</h5>
                        <p class="card-text">Usuario Asignado: {{ $task->assigned_user_name }}</p>
                        <!-- Botones para modificar y borrar la tarea -->
                        <div class="text-center">
                            <a href="{{ route('superadmin.edit', ['id' => $task->id]) }}" class="btn btn-primary btn-sm">Modificar</a>
                            <form action="{{ route('superadmin.delete', $task->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar esta tarea?')">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

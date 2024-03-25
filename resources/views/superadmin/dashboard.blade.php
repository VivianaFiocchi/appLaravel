@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Welcome to Superadmin Dashboard</h1>

    <!-- Botón para redirigir a la vista de registro -->
    <a href="{{ route('superadmin.registro') }}" class="btn btn-primary">Registrar Nuevo Usuario</a>

    <h2>Tareas</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Título</th>
                <th>Usuario Asignado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
            <tr>
                <td>{{ $task->title }}</td>
                <td>{{ $task->assigned_user_name }}</td> <!-- Ajusta según tu relación de usuario asignado -->
                <td>
                    <form method="POST" action="{{ route('superadmin.delete', $task->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Botón para redirigir a la vista de creación de tarea -->
    <a href="{{ route('superadmin.create') }}" class="btn btn-primary">Crear Nueva Tarea</a>
</div>
@endsection

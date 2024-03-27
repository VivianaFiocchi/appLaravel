@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Eliminar Tarea</div>

                <div class="card-body">
                    <p>¿Estás seguro de que deseas eliminar esta tarea?</p>
                    <form method="POST" action="{{ route('tasks.destroy', $task->id) }}">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger">Eliminar Tarea</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

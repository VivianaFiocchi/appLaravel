@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Crear Tarea</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('tasks.store') }}">
                        @csrf

                        <div class="form-group">
                            <label for="title">Título de la Tarea</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Crear Tarea</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

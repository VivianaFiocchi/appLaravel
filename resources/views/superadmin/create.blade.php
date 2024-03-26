@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Crear Tarea</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('superadmin.store') }}">
                        @csrf

                        <div class="form-group">
                            <label for="title">Título de la Tarea</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="assigned_user_id">Usuario Asignado</label>
                            <select class="form-control" id="assigned_user_id" name="assigned_user_id" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Crear Tarea</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

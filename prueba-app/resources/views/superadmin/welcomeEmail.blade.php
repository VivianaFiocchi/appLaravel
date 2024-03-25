@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Bienvenido a nuestra plataforma, {{ $user->name }}!</div>

                <div class="card-body">
                    <p>
                        Gracias por registrarte en nuestra plataforma. Estamos encantados de tenerte a bordo.
                    </p>
                    
                    <p>
                        Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos.
                    </p>

                    <!-- Enlace para configurar la contraseña -->
                    <p>
                        Por favor, <a href="{{ route('password.setup') }}">configura tu contraseña</a> para continuar.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

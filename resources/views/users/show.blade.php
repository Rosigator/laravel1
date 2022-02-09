@extends('layouts.layout')

@section('title', $user->name)

@section('content')

    <style>
        .tarjeta {
            width: 50%;
        }

        .boton {
            width: 15%;
            margin: 0 1rem;
        }

    </style>

    <div class="card tarjeta">
        <div class="card-body">
            <h4 class="card-header pb-4">Información del Usuario</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item py-3">
                        <div class="row">
                            <div class="col-sm-3 font-weight-bold">Nombre:</div>
                            <div class="col-sm-9">{{ $user->name }}</div>
                        </div>
                    </li>
                    <li class="list-group-item py-3">
                        <div class="row">
                            <div class="col-sm-3 font-weight-bold">Email:</div>
                            <div class="col-sm-9">{{ $user->email }}</div>
                        </div>
                    </li>
                    <li class="list-group-item py-3">
                        <div class="row">
                            <div class="col-sm-3 font-weight-bold">Profesión:</div>
                            <div class="col-sm-9">
                                {{ isset($user->profile->profession) ? $user->profile->profession->title : 'Ninguna' }}
                            </div>
                    </li>
                    <li class="list-group-item py-3">
                        <div class="row">
                            <div class="col-sm-3 font-weight-bold">Twitter:</div>
                            <div class="col-sm-9"><a
                                    href="{{ $user->profile->twitter }}">{{ $user->profile->twitter }}</a>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item py-3">
                        <div class="row">
                            <div class="col-sm-3 font-weight-bold">Bio:</div>
                            <div class="col-sm-9">{{ $user->profile->bio }}</div>
                        </div>
                    </li>
                </ul>
                <div class="card-footer row py-3">
                    <a class="btn btn-warning boton" href="{{ route('user.edit', $user) }}">Editar</a>
                    <a class="btn btn-primary boton" href="{{ route('user.index') }}">Volver</a>
                </div>
        </div>
    </div>



@stop

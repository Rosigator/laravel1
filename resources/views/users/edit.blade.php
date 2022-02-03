@extends('layouts.layout')

@section('title', 'Edición')

@section('content')

    <div class="card w-50 pe-5">

        <h3 class="m-3 mb-5 card-header pb-4">Editando información del usuario #{{ $user->id }}</h3>

        <div class="card-body">
            @if ($errors->any())

                <div class="alert alert-danger">

                    <p>Por favor, corrige los siguientes errores:</p>

                    <ul>

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <form class="ms-3" method="POST" action="{{ url("usuarios/{$user->id}") }}" id="editform">

                {{ method_field('PUT') }}
                {{ csrf_field() }}

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label" for="name">Nombre: </label>
                    <input class="col-sm-10" type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label" for="email">Email: </label>
                    <input class="col-sm-10" type="email" name="email" id="email"
                        value="{{ old('email', $user->email) }}">
                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label" for="profession">Profesión: </label>
                    <select class="col-sm-10" name="profession" id="profession">

                        <option value="empty" selected></option>

                        @foreach ($professions as $profession)

                            <option value="{{ $profession }}">{{ $profession }}</option>

                        @endforeach

                    </select>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label" for="password">Contraseña: </label>
                    <input class="col-sm-10" type="password" name="password" id="password">
                </div>

            </form>

            <button form="editform" class="btn btn-success m-3" type="submit">Guardar</button>
            <a href="{{ route('user.index') }}"><button class="btn btn-secondary m-3">Volver</button></a>
        </div>
    </div>
@stop

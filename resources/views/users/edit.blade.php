@extends('layouts.layout')

@section('title', 'Edición')

@section('content')

    <style>
        form div,
        .back {
            margin: 1rem 0;
        }

    </style>

    <h1>Editando información del usuario {{ $user->id }}</h1>

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

    <form method="POST" action="{{ url('usuarios/editar') }}">

        {{ csrf_field() }}

        <div>
            <label for="name">Nombre: </label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
        </div>

        <div>
            <label for="email">Email: </label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}">
        </div>

        <div>
            <label for="profession">Profesión: </label>
            <select name="profession" id="profession">

                <option value="empty" selected></option>

                @foreach ($professions as $profession)

                    <option value="{{ $profession }}">{{ $profession }}</option>

                @endforeach

            </select>
        </div>

        <div>
            <label for="password">Contraseña: </label>
            <input type="password" name="password" id="password">
        </div>

        <button type="submit">Guardar</button>

    </form>

    <a href="{{ route('user.index') }}"><button class="back">Volver</button></a>

@stop

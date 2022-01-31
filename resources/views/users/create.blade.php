@extends('layouts.layout')

@section('title', 'Creación')

@section('content')

    <style>
        form div,
        .back {
            margin: 1rem 0;
        }

    </style>

    <h1>Creación de nuevo usuario</h1>

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

    <form method="POST" action="{{ url('usuarios/crear') }}">

        {{ csrf_field() }}

        <div>
            <label for="name">Nombre: </label>
            <input type="text" name="name" id="name" value="{{ old('name') }}">
        </div>

        <div>
            <label for="email">Email: </label>
            <input type="email" name="email" id="email" value="{{ old('email') }}">
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

        <button type="submit">Crear</button>

    </form>

    <a href="{{ route('user.index') }}"><button class="back">Volver</button></a>

@stop

@extends('layouts.layout')

@section('title', 'Usuario #' . $user->id)

@section('content')
    <style>
        .botones-abajo {
            display: block;
            margin: 1rem 1rem;
        }

    </style>

    <h3 style="text-decoration: underline">{{ $user->name }}</h3>
    <ul>
        <li>Email: {{ $user->email }}</li>
        <li>Profesión: {{ isset($user->profession) ? $user->profession->title : '' }}</li>
    </ul>
    <a class="botones-abajo" href="{{ route('user.edit', $user) }}"><button>Editar</button></a>
    <a class="botones-abajo" href="{{ route('user.index') }}"><button>Volver</button></a>

@stop

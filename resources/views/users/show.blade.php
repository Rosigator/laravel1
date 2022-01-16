@extends('layouts.layout')

@section('title', 'Usuario #' . $user->id)

@section('content')

    <h3 style="text-decoration: underline">{{ $user->name }}</h3>
    <ul>
        <li>Email: {{ $user->email }}</li>
        <li>Profesión: {{ $user->profession->title }}</li>
    </ul>
    <a href="{{ route('user.index') }}"><button>Volver</button></a>

@stop

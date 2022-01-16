@extends('layouts.layout')

@section('title', 'Usuarios')

@section('content')

    <h1> {{ $title }} </h1>
    <ul>
        @forelse ($users as $user)
            <li>{{ $user->name }} <a href="{{ route('user.show', ['id' => $user->id]) }}">Ver Detalles</a>
            </li>
        @empty
            <li>No hay usuarios registrados</li>
        @endforelse
    </ul>

@stop

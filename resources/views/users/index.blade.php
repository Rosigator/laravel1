@extends('layouts.layout')

@section('title', 'Usuarios')

@section('content')

    <h1> {{ $title }} </h1>
    <ul>
        @forelse ($users as $user)
            <li>{{ $user }}</li>
        @empty
            <li>No hay usuarios registrados</li>
        @endforelse
    </ul>

@stop

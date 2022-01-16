@extends('layouts.layout')

@section('title', 'Página no encontrada')

@section('content')
    <style>
        main * {
            text-align: center;
        }

        h1 {
            margin-top: 7rem;
        }

        img,
        button {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        a {
            text-decoration: none;
        }

    </style>
    <h1>Lo sentimos, el usuario no ha sido encontrado</h1>
    <img src="{{ asset('img/errors/404_alien_image.jpg') }}" alt="imagen 404">
    <a href="{{ route('user.index') }}"><button>Volver</button></a>

@stop

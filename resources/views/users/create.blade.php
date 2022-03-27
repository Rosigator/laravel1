@extends('layouts.layout')

@section('title', 'Creación')

@section('content')

    @card

    @slot('header', 'Creación de nuevo usuario')


    @include('shared._errors')

    <form class="ms-3" method="POST" action="{{ url('usuarios/crear') }}" id="createform">

        @include('users._fields')

    </form>

    <button form="createform" class="btn btn-primary m-3" type="submit">Crear</button>
    <a href="{{ route('user.index') }}"><button class="btn btn-secondary m-3">Volver</button></a>

    @endcard

@stop

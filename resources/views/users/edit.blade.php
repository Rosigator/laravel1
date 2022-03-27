@extends('layouts.layout')

@section('title', 'Edición')

@section('content')
    @card

    @slot('header', "Editando información del usuario #{$user->id}")

    @include('shared._errors')

    <form class="ms-3" method="POST" action="{{ url("usuarios/{$user->id}") }}" id="editform">

        {{ method_field('PUT') }}

        @include('users._fields')

    </form>

    <button form="editform" class="btn btn-success m-3" type="submit">Guardar</button>
    <a href="{{ route('user.index') }}"><button class="btn btn-secondary m-3">Volver</button></a>

    @endcard
@stop

@extends('layouts.layout')

@section('title', 'Creación')

@section('content')
    <div class="card w-50 pe-5">
        <h3 class="m-3 mb-5 card-header pb-4">Creación de nuevo usuario</h3>
        <div class="card-body">

            @include('shared._errors')

            <form class="ms-3" method="POST" action="{{ url('usuarios/crear') }}" id="createform">

                @include('users._fields')

            </form>

            <button form="createform" class="btn btn-primary m-3" type="submit">Crear</button>
            <a href="{{ route('user.index') }}"><button class="btn btn-secondary m-3">Volver</button></a>
        </div>
    </div>
@stop

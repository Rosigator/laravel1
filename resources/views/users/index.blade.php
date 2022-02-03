@extends('layouts.layout')

@section('title', 'Usuarios')

@section('content')

    <h1 class="mt-3 mb-4"> {{ $title }} </h1>

    @if ($users->isNotEmpty())

        <table class="table">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Email</th>
                    <th scope="col" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td class="text-center">
                            <a class="text-decoration-none" href="{{ route('user.show', $user) }}">
                                <span class="oi oi-eye btn btn-link text-decoration-none w-25"></span>
                            </a>
                            <a class="text-decoration-none" href="{{ route('user.edit', $user) }}">
                                <span class="oi oi-pencil btn btn-link text-decoration-none w-25"></span>
                            </a>
                            <form method="POST" action="{{ route('user.destroy', $user) }}" class="d-inline">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button class="btn btn-link w-25" type="submit">
                                    <span class="oi oi-trash"></span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <h2>No hay usuarios registrados</h2>
    @endif

@stop

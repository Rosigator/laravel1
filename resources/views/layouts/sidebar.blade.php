@extends('layouts.layout')

@section('middle')

    <main class="mt-5 pt-3 mx-2">
        <div class="row">
            <div class="col-10">

                @yield('content')

            </div>
            <div class="col-2 bg-success">

                @yield('sidebar')

            </div>
        </div>
    </main>

@stop

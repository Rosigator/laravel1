<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        if (request()->has('empty')) {
            $users = [];
        } else {
            $users = [
                'Ana',
                'Pedro',
                'Juan',
                'Manolo',
            ];
        }

        $title = 'Lista de Usuarios';

        return view('users.index', compact('users', 'title'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function show($id)
    {
        return view('users.show')->with(compact('id'));
    }

    public function edit($id)
    {
        return view('users.edit')->with(compact('id'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = [
            'Ana',
            'Pedro',
            'Juan',
            '<script>alert(1)</script>',
        ];

        $title = 'Listado de Usuarios';

        return view('users', compact('users', 'title'));
    }

    public function create()
    {
        return 'Creando un usuario';
    }

    public function show($id)
    {
        return 'Mostrando detalles del usuario ' . $id;
    }

    public function edit($id)
    {
        return 'Editando información del usuario ' . $id;
    }
}

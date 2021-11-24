<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return 'Estás viendo los usuarios';
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

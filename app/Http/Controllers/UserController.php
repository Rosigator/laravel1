<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        $title = 'Lista de Usuarios';

        return view('users.index', compact('users', 'title'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        return view('users.edit', compact('id'));
    }
}

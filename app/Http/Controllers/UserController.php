<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Profession;

class UserController extends Controller
{
    //Mostrar todos los usuarios
    public function index()
    {
        $users = User::all();

        $title = 'Lista de Usuarios';

        return view('users.index', compact('users', 'title'));
    }

    // Mostrar info de un usuario
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    //Edición de datos de usuario
    public function edit(User $user)
    {
        $professions = Profession::pluck('title');

        return view('users.edit', compact('user', 'professions'));
    }

    // Creación de usuarios
    public function create()
    {
        $professions = Profession::pluck('title');

        return view('users.create', compact('professions'));
    }

    public function store()
    {
        $data = request()->validate([
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users,email'],
            'profession' => 'exists:professions,title',
            'password' => ['required', 'min:6']
        ]);

        $profession_id = Profession::where('title', $data['profession'])->value('id');

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'profession_id' => $profession_id,
            'password' => bcrypt($data['password'])
        ]);

        return redirect('usuarios');
    }
}

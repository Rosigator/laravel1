<?php

namespace App\Http\Controllers;

use App\User;
use App\Skill;
use App\Profession;
use Illuminate\Http\Request;
use App\UserProfile as UserProfile;
use Illuminate\Validation\Rule as Rule;
use Illuminate\Support\Facades\DB as DB;
use App\Http\Requests\CreateUserRequest as CreateUserRequest;

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

    // Creación de usuarios
    public function create()
    {
        $professions = Profession::orderBy('title', 'ASC')->get();
        $skills = Skill::orderBy('name', 'ASC')->get();
        $roles = trans('users.roles');
        $user = new User();

        return view('users.create', compact('user', 'professions', 'skills', 'roles'));
    }

    public function store(CreateUserRequest $request)
    {
        $request->createUser();

        return redirect('usuarios');
    }

    //Edición de datos de usuario
    public function edit(User $user)
    {
        $professions = Profession::orderBy('title', 'ASC')->get();
        $skills = Skill::orderBy('name', 'ASC')->get();
        $roles = trans('users.roles');

        return view('users.edit', compact('user', 'professions', 'skills', 'roles'));
    }

    public function update(User $user)
    {
        $data = request()->validate([
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'profession' => 'exists:professions,title',
            'password' => ''
        ]);

        if ($data['password'] != null) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect("usuarios/{$user->id}");
    }

    // Eliminación de usuarios
    public function destroy(User $user)
    {
        $user->profile->delete();
        $user->delete();

        return redirect('usuarios');
    }
}

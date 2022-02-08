<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Profession;
use Illuminate\Validation\Rule as Rule;
use App\UserProfile as UserProfile;
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

    //Edición de datos de usuario
    public function edit(User $user)
    {
        $professions = Profession::all()->filter(function ($value) use ($user) {
            return $value->id != $user->profession->id;
        });

        return view('users.edit', compact('user', 'professions'));
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

    // Creación de usuarios
    public function create()
    {
        $professions = Profession::orderBy('title', 'ASC')->get();

        return view('users.create', compact('professions'));
    }

    public function store(CreateUserRequest $request)
    {
        $request->createUser();

        return redirect('usuarios');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect('usuarios');
    }
}

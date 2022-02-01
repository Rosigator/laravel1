<?php

//Esto se lo pongo yo pa que no me raye el que me ponga la clase Route como desconocida en todas las rutas.

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//Inicio
Route::view('/', 'welcome')
    ->name('index');

//Índice de usuarios
Route::get('/usuarios', 'UserController@index')
    ->name('user.index');

//Información de un usuario
Route::get('/usuarios/{user}', 'UserController@show')
    ->where('user', '[0-9]+')
    ->name('user.show');

//Edición de Usuarios
Route::get('usuarios/{user}/editar', 'UserController@edit')
    ->where('user', '\d+')
    ->name('user.edit');

Route::put('usuarios/{user}', 'UserController@update')
    ->where('user', '\d+')
    ->name('user.update');

//Creación de Usuarios
Route::get('usuarios/nuevo', 'UserController@create')
    ->name('user.new');

Route::post('usuarios/crear', 'UserController@store');

//Saludo a un Usuario
Route::get('/usuarios/{name}/{nickname?}', 'WelcomeUserController')
    ->where('name', '[a-zA-Z]+')
    ->where('nickname', '[a-zA-Z]+')
    ->name('user.welcome');

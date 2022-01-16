<?php

//Esto se lo pongo yo pa que no me raye el que me ponga la clase Route como desconocida en todas las rutas.
use Illuminate\Support\Facades\Route;

//Método que sirve solo para devolver una vista, mucho más sencillo que el anterior
Route::view('/', 'welcome')
    ->name('index');

Route::get('/usuarios', 'UserController@index')
    ->name('user.index');

Route::get('/usuarios/{user}', 'UserController@show')
    ->where('user', '[0-9]+')
    ->name('user.show');

Route::get('usuarios/{id}/editar', 'UserController@edit')
    ->where('id', '\d+')
    ->name('user.edit');

Route::get('usuarios/crear', 'UserController@create')
    ->name('user.create');

Route::get('/usuarios/{name}/{nickname?}', 'WelcomeUserController')
    ->where('name', '[a-zA-Z]+')
    ->where('nickname', '[a-zA-Z]+')
    ->name('user.welcome');

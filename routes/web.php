<?php

//Esto se lo pongo yo pa que no me raye el que me ponga la clase Route como desconocida en todas las rutas.
use Illuminate\Support\Facades\Route;

//Método que sirve solo para devolver una vista, mucho más sencillo que el anterior
Route::view('/', 'welcome');

Route::get('/usuarios', 'UserController@index');

Route::get('/usuarios/{id}', 'UserController@show')
    ->where('id', '[0-9]+');

Route::get('usuarios/{id}/editar', 'UserController@edit')
    ->where('id', '\d+');

Route::get('usuarios/crear', 'UserController@create');

Route::get('/usuarios/{name}/{nickname?}', 'WelcomeUserController')
    ->where('name', '[a-zA-Z]+')
    ->where('nickname', '[a-zA-Z]+');

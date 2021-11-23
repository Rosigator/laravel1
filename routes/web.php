<?php

//Esto se lo pongo yo pa que no me raye el que me ponga la clase Route como desconocida en todas las rutas.
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

//Método que sirve solo para devolver una vista, mucho más sencillo que el anterior
Route::view('/', 'welcome');

Route::get('/usuarios', function () {
    return 'Estás viendo los usuarios';
});

// Route::get('/usuarios', function () {
//     return 'Estás viendo los datos del usuario ' . $_GET['id'];
// });

Route::get('/usuarios/{id}', function ($id) {
    return 'Mostrando detalles del usuario ' . $id;
})->where('id', '[0-9]+');

Route::get('usuarios/crear', function () {
    return 'Creando un usuario';
});

Route::get('/usuarios/{name}/{nickname?}', function ($name, $nickname = null) {
    $common = 'Bienvenido ' . ucfirst($name) . '. ';
    return $nickname
        ? $common . 'Tu apodo es: ' . $nickname
        : $common;
});

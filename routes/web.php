<?php

Route::get('/', function () {
    return view('welcome');
});

Route::get('/usuarios', function () {
    return 'Estás viendo los usuarios';
});

// Route::get('/usuarios', function () {
//     return 'Estás viendo los datos del usuario ' . $_GET['id'];
// });

Route::get('/usuarios/{id}', function ($id) {
    return 'Mostrando detalles del usuario ' . $id;
})->where('id', '[0-9]+');

Route::get('/usuarios/{name}/{nickname?}', function ($name, $nickname = null) {
    $common = 'Bienvenido ' . ucfirst($name) . '. ';
    return $nickname
        ? $common . 'Tu apodo es: ' . $nickname
        : $common;
});

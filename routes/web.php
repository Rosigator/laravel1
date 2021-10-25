<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

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
    $common = 'Bienvenido ' . $name . '. ';
    return $nickname
        ? $common . 'Tu apodo es: ' . $nickname
        : $common;
});

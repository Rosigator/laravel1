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

//Una simple cosa de prueba
Route::get('/usuarios/{id}', function ($id) {
    $val = intval($id);

    if (is_int($val) && $val > 0) {
        return 'Estás viendo el perfil del usuario ' . $val;
    } else {
        return 'Id de usuario incorrecta';
    }
});

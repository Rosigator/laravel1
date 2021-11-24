<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeUserController extends Controller
{
    public function __invoke($name, $nickname = null)
    {
        $common = 'Bienvenido ' . ucfirst($name) . '. ';
        return $nickname
            ? $common . 'Tu apodo es: ' . $nickname
            : $common;
    }
}

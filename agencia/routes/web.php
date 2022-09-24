<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});
//Route::get('peticion', acción);
Route::get('/manzana', function ()
{
    $nombre = 'marcos';
    $numero = 7;
    return view('vistaManzana',
                    [
                        'persona'=>$nombre,
                        'numero'=>$numero
                    ]
                );
});

Route::get('/inicio', function ()
{
    return view('inicio');
});


<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});
//Route::get('peticion', acción);
Route::get('/manzana', function ()
{
    return view('vistaManzana');
});

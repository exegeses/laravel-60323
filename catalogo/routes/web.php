<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

/*### CRUD de marcas ###*/
use App\Http\Controllers\MarcaController;
Route::get('/marcas', [ MarcaController::class, 'index' ]);

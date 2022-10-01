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
Route::view('/form', 'form' );
Route::post('/procesa', function ()
{
    //$nombre = $_POST['nombre'];
    $nombre = request()->nombre;
    return $nombre;
});
// test Ayrton para la conexión
Route::view('/testConnection', 'testConnection');

/*###  CRUD DE REGIONES ###*/
Route::get('/regiones', function ()
{
    //obtenemos listado de regiones
    $regiones = DB::select('SELECT idRegion, regNombre
                                FROM regiones');
    return view('regiones', [ 'regiones'=>$regiones ]);
});

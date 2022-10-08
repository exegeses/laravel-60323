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
Route::get('/region/create', function ()
{
    return view('regionCreate');
});
Route::post('/region/store', function ()
{
    $regNombre = request()->regNombre;
    //insertamos dato en tabla regiones
    try{
        DB::insert('INSERT INTO regiones
                            ( regNombre )
                        VALUES
                            ( :regNombre )',
                            [ $regNombre ]
                   );
        return redirect('/regiones')
                ->with([
                        'mensaje'=>'Región: '.$regNombre.' agregada correctamente',
                        'css'=>'success'
                       ]);
    }
    catch ( \Throwable $th )
    {
        return redirect('/regiones')
            ->with([
                'mensaje'=>'No se puedo agregar la región: '.$regNombre,
                'css'=>'danger'
            ]);
    }
});
Route::get('/region/edit/{id}', function ( $id )
{
    //obtener datos de la región
    /* rawSQL
    $region = DB::select('SELECT idRegion, regNombre
                            FROM regiones
                            WHERE idRegion = :id',
                                    [ $id ]); */
    /* fluent Query Builder */
    $region = DB::table('regiones')
                    ->where('idRegion', $id)
                    ->first();
    //retornar vista de form para modificar
    return view('regionEdit', [ 'region'=>$region ]);
});
Route::patch('/region/update', function ()
{
    //capturamos datos enviados por el form
    $regNombre = request()->regNombre;
    $idRegion = request()->idRegion;
    try {
        /* rawSQL
        DB::update('UPDATE regiones
                        SET regNombre = :regNombre
                        WHERE idRegion = :idRegion',
                            [ $regNombre, $idRegion ]);*/
        DB::table('regiones')
                ->where('idRegion', $idRegion)
                ->update( [ 'regNombre'=>$regNombre ] );
        return redirect('/regiones')
                ->with(
                    [
                        'mensaje'=>'Región: '.$regNombre.' modificada correctamente.',
                        'css'=>'success'
                    ]
                );
    }
    catch ( \Throwable $th )
    {
        return redirect('/regiones')
            ->with([
                'mensaje'=>'No se puedo agregar la región: '.$regNombre,
                'css'=>'danger'
            ]);
    }
});
Route::get('/region/delete/{id}', function ($id)
{
    //obtenemnos datos de la región
    $region = DB::table('regiones')
        ->where('idRegion', $id)
        ->first();
    //saber si hay destinos x región
    $cantidad = DB::table('destinos')
                    ->where('idRegion', $id)
                    ->count();
    if( $cantidad ){
        return redirect('/regiones')
                ->with([
                    'mensaje'=>'Región: '.$region->regNombre.' no se puede eliminar ya que tiene destinos relacionados.',
                    'css'=>'warning'
                ]);
    }
    return view('regionDelete', [ 'region'=>$region ]);
});
Route::delete('/region/destroy', function ()
{
    $idRegion = request()->idRegion;
    $regNombre = request()->regNombre;
    try {
        /* rawSQL
        DB::delete('DELETE FROM regiones
                        WHERE idRegion = :idRegion',
                            [ $idRegion ]); */
        DB::table('regiones')
                ->where('idRegion', $idRegion)
                ->delete();
        return redirect('/regiones')
            ->with([
                'mensaje'=>'Región: '.$regNombre.' eliminada correctamente',
                'css'=>'success'
            ]);

    }
    catch ( \Throwable $th )
    {
        return redirect('/regiones')
            ->with([
                'mensaje'=>'No se puedo eliminar la región: '.$regNombre,
                'css'=>'danger'
            ]);
    }
});

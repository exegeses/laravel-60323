<?php

namespace App\Http\Controllers;


use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //obtenemos listado de marcas
        $marcas = Marca::paginate(7);
        //retornamos vista
        return view('marcas', [ 'marcas'=>$marcas ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('marcaCreate');
    }

    private function validarForm( Request $request )
    {
        $request->validate(
            /*[ 'campo'=>'reglas' ], [ 'campo.regla'=>'mensaje' ]*/

            [ 'mkNombre'=>'required|unique:marcas|min:2|max:15' ],
            [
                'mkNombre.required'=>'El campo "Nombre de la marca" es obligatorio',
                'mkNombre.unique'=>'Esa marca ya existe',
                'mkNombre.min'=>'El campo "Nombre de la marca" debe tener al menos 2 caractéres',
                'mkNombre.max'=>'El campo "Nombre de la marca" debe tener 15 caractéres como máximo'
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validación
        $this->validarForm($request);
        //si pasó la validación, continúa

        $mkNombre = $request->mkNombre;

        try {
            //instanciamos
            $Marca = new Marca;
            //asignamos valor a los atributos
            $Marca->mkNombre = $mkNombre;
            //guardamos en tabla marcas
            $Marca->save();
            return redirect('/marcas')
                    ->with(
                        [
                            'mensaje'=>'Marca: '.$mkNombre.' agregada correctamente.',
                            'css'=>'success'
                        ]
                    );
        }
        catch ( \Throwable $th ){
            return redirect( '/marcas' )
                    ->with(
                        [
                            'mensaje'=>'No se pudo agregar la marca: '.$mkNombre,
                            'css'=>'danger'
                        ]
                    );
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //obtenemos datos de una marca por su id
        $Marca = Marca::find($id);
        return view('marcaEdit', [ 'Marca'=>$Marca ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //validación
        $this->validarForm($request);

        $mkNombre = $request->mkNombre; //request()->
        try {
            //obtenemos datos de una marca por su id
            $Marca = Marca::find( $request->idMarca );
            //reasignamos atributos
            $Marca->mkNombre = $mkNombre;
            //guardamos cambios
            $Marca->save();
            //redirección con masaje ok
            return redirect('/marcas')
                ->with(
                    [
                        'mensaje'=>'Marca: '.$mkNombre.' modificada correctamente.',
                        'css'=>'success'
                    ]
                );
        }
        catch ( \Throwable $th ){
            return redirect( '/marcas' )
                ->with(
                    [
                        'mensaje'=>'No se pudo modificar la marca: '.$mkNombre,
                        'css'=>'danger'
                    ]
                );
        }
    }

    private function checkProducto( $idMarca )
    {
        //$Producto = Producto::where( 'idMarca', $idMarca )->first();
        $Producto = Producto::firstWhere('idMarca', $idMarca);
        return $Producto;
        //$cantidad = Producto::where( 'idMarca', $idMarca )->count();
        //return $cantidad;
    }

    /**
     * Método para confirmar si hay Productos de esa marca
     * @param $id
     * @return redirect | view
     */
    public function confirm($id)
    {
        //obtenemos datos de la marca
        $Marca = Marca::find($id);

        //si NO hay productos de esa marca
        if( !$this->checkProducto($id) ){
            return view('marcaDelete', [ 'Marca'=>$Marca ]);
        }
        //si hay productos
        return redirect('marcas')
                ->with( [
                        'mensaje'=>'No se puede eliminar la marca: '.$Marca->mkNombre.' ya que tiene productos relacionados.',
                        'css'=>'warning'
                        ] );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request )
    {
        $mkNombre = $request->mkNombre;
        try {
            /*$Marca = Marca::find($request->idMarca);
            $Marca->delete();*/
            Marca::destroy($request->idMarca);
            //redirección con masaje ok
            return redirect('/marcas')
                ->with(
                    [
                        'mensaje'=>'Marca: '.$mkNombre.' eliminada correctamente.',
                        'css'=>'success'
                    ]
                );
        }
        catch ( \Throwable $th ){
            return redirect( '/marcas' )
                ->with(
                    [
                        'mensaje'=>'No se pudo eliminar la marca: '.$mkNombre,
                        'css'=>'danger'
                    ]
                );
        }
    }
}

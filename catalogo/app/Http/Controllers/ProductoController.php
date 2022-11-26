<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /*$p = Producto::menor('1000')->alfa()->get();
        dd($p);*/
        $productos = Producto::with(['getMarca', 'getCategoria'])
                                ->paginate(5);
        return view('productos',
            [
                'productos'=>$productos
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //obtenemos listados de marcas y de categorías
        $marcas = Marca::all();
        $categorias = Categoria::all();
        return view('productoCreate',
                [
                    'marcas'=>$marcas,
                    'categorias'=>$categorias
                ]);
    }

    private function validarForm( Request $request, $idProducto = null )
    {
        $request->validate(
            [
                'prdNombre'=>'required|unique:productos,prdNombre,'.$idProducto.',idProducto|min:3|max:30',
                'prdPrecio'=>'required|numeric|min:0',
                'idMarca'=>'required',
                'idCategoria'=>'required',
                'prdDescripcion'=>'required|max:255',
                'prdImagen'=>'mimes:jpg,jpeg,png,gif,svg,webp|max:2048'
            ],
            [
                'prdNombre.required'=>'El campo "Nombre del producto" es obligatorio.',
                'prdNombre.unique'=>'El "Nombre del producto" ya existe.',
                'prdNombre.min'=>'El campo "Nombre del producto" debe tener como mínimo 3 caractéres.',
                'prdNombre.max'=>'El campo "Nombre" debe tener 30 caractéres como máximo.',
                'prdPrecio.required'=>'Complete el campo Precio.',
                'prdPrecio.numeric'=>'Complete el campo Precio con un número.',
                'prdPrecio.min'=>'Complete el campo Precio con un número mayor a 0.',
                'idMarca.required'=>'Seleccione una marca.',
                'idCategoria.required'=>'Seleccione una categoría.',
                'prdDescripcion.required'=>'Complete el campo Descripción.',
                'prdDescripcion.max'=>'Complete el campo Descripción con 255 caractéres como máxino.',
                'prdImagen.mimes'=>'Debe ser una imagen.',
                'prdImagen.max'=>'Debe ser una imagen de 2MB como máximo.'
            ]
        );
    }

    private function subirImagen( Request $request ) : string
    {
        //si no enviaron imagen store()
        $prdImagen = 'noDisponible.jpg';

        //si no enviaron imagen update()
        if( $request->has('imgActual') ){
            $prdImagen = $request->imgActual;
        }

        //si enviaron imagen
        if( $request->file('prdImagen') ){
            $file = $request->file('prdImagen');
            $time = time();
            $ext = $file->getClientOriginalExtension();
            $prdImagen = $time.'.'.$ext;
            //subir en /imagenes/productos
            $file->move( public_path('imagenes/productos/'), $prdImagen );
        }
        return $prdImagen;
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
        $prdNombre = $request->prdNombre;
        $prdPrecio = $request->prdPrecio;
        $idMarca = $request->idMarca;
        $idCategoria = $request->idCategoria;
        $prdDescripcion = $request->prdDescripcion;
        $prdImagen = $this->subirImagen($request);
        try{
            $Producto = new Producto;
            //asignamos atributos
            $Producto->prdNombre = $prdNombre;
            $Producto->prdPrecio = $prdPrecio;
            $Producto->idMarca = $idMarca;
            $Producto->idCategoria = $idCategoria;
            $Producto->prdDescripcion = $prdDescripcion;
            $Producto->prdImagen = $prdImagen;
            $Producto->prdActivo = 1;
            //almacenamos en tabla productos
            $Producto->save();

            return redirect('/productos')
                ->with([
                        'mensaje'=>'Producto: '.$prdNombre.' agregado correctmente.',
                        'css'=>'success'
                    ]);
        }
        catch (\Throwable $th) {
            return redirect("/productos")
                ->with(
                    [
                        'mensaje' => 'No se pudo agregar el producto: '. $prdNombre,
                        'css' => 'danger'
                    ]
                );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
    {
        //obtenemos listado de marcas y de categorías
        $marcas = Marca::all();
        $categorias = Categoria::all();
        //obtenemos datos de producto
        $Producto = Producto::find( $id );
        return view('productoEdit',
                [
                    'marcas'=>$marcas,
                    'categorias'=>$categorias,
                    'Producto'=>$Producto
                ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request )
    {
        //validación
        $this->validarForm( $request, $request->idProducto );/*VER*/
        //subir imagen
        $prdImagen = $this->subirImagen( $request );
        try {
            $idProducto = $request->idProducto;
            $prdNombre = $request->prdNombre;
            $prdPrecio = $request->prdPrecio;
            $idMarca = $request->idMarca;
            $idCategoria = $request->idCategoria;
            $prdDescripcion = $request->prdDescripcion;
            $Producto = Producto::find( $idProducto );
            //asignamos atributos
            $Producto->prdNombre = $prdNombre;
            $Producto->prdPrecio = $prdPrecio;
            $Producto->idMarca = $idMarca;
            $Producto->idCategoria = $idCategoria;
            $Producto->prdDescripcion = $prdDescripcion;
            $Producto->prdImagen = $prdImagen;
            $Producto->save();
            return redirect('/productos')
                ->with([
                    'mensaje'=>'Producto: '.$prdNombre.' modificado correctmente.',
                    'css'=>'success'
                ]);
        }
        catch (\Throwable $th) {
            return redirect("/productos")
                ->with(
                    [
                        'mensaje' => 'No se pudo modificar el producto: '. $prdNombre,
                        'css' => 'danger'
                    ]
                );
        }
    }

    public function delete( $id )
    {
        //obtenemos datos de producto
        $Producto = Producto::with(['getMarca', 'getCategoria'])->find( $id );
        return view('productoDelete', [ 'Producto'=>$Producto ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request )
    {
        $prdNombre = $request->prdNombre;
        try {
            Producto::destroy($request->idProducto);
            return redirect('/productos')
                ->with([
                    'mensaje'=>'Producto: '.$prdNombre.' eliminado correctmente.',
                    'css'=>'success'
                ]);
        }
        catch (\Throwable $th) {
            return redirect("/productos")
                ->with(
                    [
                        'mensaje' => 'No se pudo eliminar el producto: '. $prdNombre,
                        'css' => 'danger'
                    ]
                );
        }
    }
}

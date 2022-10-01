@extends( 'layouts.plantilla' )

    @section('contenido')

        <h1>Formulario de envío</h1>
        <div class="alert bg-light p-4 col-8 mx-auto shadow">
            <form action="/procesa" method="post">
            @csrf
                Nombre: <br>
                <input type="text" name="nombre" class="form-control">
                <br>
                <button class="btn btn-dark">enviar</button>
            </form>
        </div>

    @endsection

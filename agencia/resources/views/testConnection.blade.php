@extends( 'layouts.plantilla' )

    @section('contenido')

        <h1>Desarrollo</h1>

        @php
            if( DB::connection()->getDatabaseName() )
        {
           echo "Yes! successfully connected to the DB: " . DB::connection()->getDatabaseName();
        }
        @endphp


    @endsection

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vista Manzana</title>
</head>
<body>
    <h1>Soy una vista</h1>
    <p>
        {{ $persona }} pediste manzanas {{ date('d/m/Y') }}
    </p>

    <ul>
    @for( $n=1; $n<=$numero; $n++ )
        <li>{{ $n }}</li>
    @endfor
    </ul>

</body>
</html>

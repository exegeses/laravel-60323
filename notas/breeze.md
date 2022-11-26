# Laravel Breeze

> Es un kit de inicio 

  [] registro de usuarios
  [] autenticación
        ->middleware(['auth'])

Route::get('peticion', [ acción ])
            ->middleware(['auth'])

  [] trae sistema de vistas usando BLADE + TAILWINDCSS
  
------
1.- crear proyecto nuevo
2.- crear nueva base de datos 
    breeze60323
3.- configurar .env para conectar a mysql
------
Migraciones
4.- correr migraciones
    php artisan migrate 
------
5.- descargar breeze
    composer require laravel/breeze --dev
6.- instalar breeze
    php artisan breeze:install
    
    scaffolding - andamiaje
        Blade + alpine.js



Ruta con autenticación.
Route::get('peticion', [ acción ])
            ->middleware(['auth']);

Ruta con nombre
web.php
Route::get('/dashboard', [ acción ])
            ->middleware(['auth'])
            ->name( 'dashboard' );
Route::get('/marcas', [ acción ])
            ->middleware(['auth'])
            ->name( 'marcas' );

vistas
    <a href="route('dashboard')" routeIs('dashboard')>
    <a href="route('marcas')" routeIs('marcas')>
    <a href="route('productos')" routeIs('productos')>
    
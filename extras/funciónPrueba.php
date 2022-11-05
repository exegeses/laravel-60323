<?php

    /**
     * función para dividar dos números
     * @param float $dividendo
     * @param float $divisor
     * @return float|string
     */

    function dividir( float $dividendo, float $divisor ) : float | string
    {
        try{
            $resultado = $dividendo / $divisor;
            return $resultado;
        }
        catch ( Error $error ){
            return 'Explotó';
        }
    }


    echo dividir();
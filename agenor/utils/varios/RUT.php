<?php
namespace agenor\utils\varios;


/**
 * Clase RUT.
 * Se le pasa un numero de RUT y nos dice si efectivamente es un numero valido o no.
 * Uruguay usa Modulo de 11.
 * 
 * XX-YYYYYY-ZZZ-V
 * X : Codigo del Departamento.
 * Y : Numero Asignado por DGI.
 * Z : Cantidad de Sucursales.
 * V : Digito Verificador.
 * 2 Primeras Posiciones entre 01 y 21
 * de 3 a 9 entre 1 y 999999 
 * O sea un RUT siempre va a tener 12 Digitos.
 */
class RUT
{
    
    public static function verificar($rut)
    {
        // Si el largo es distinto de 12 ya se que no es RUT
        if (strlen($rut) != 12 || !is_numeric($rut)) {
            return false;
        }
        // Se toman los 8 primeros digitos XX-YYYYYY
        echo $dig8       = substr($rut, 0,8) . '0000';
        // El arreglo tiene que ser 43298765432 si no das vuelta el nro o 
        // si das vuelta el nro como hiciste en tus calculos es 23456789234
        $testigo    = "43298765432";
                       
        
        
        
        echo $m = self::_multiplicador($rut, $testigo);
        echo $modulo = $m % 11;
        echo '<br>|';
        echo  (11 - $modulo);
    }
    
    
    
    private static function _multiplicador($xy, $testigo) 
    {
        $multiplicador  = 0;
        
        for ($f=0; $f < 8; $f++) {
            $multiplicador += $xy[$f] * $testigo[$f];
        }
        return $multiplicador; 
    }
    
    public static function ver2($rut)
    {
        $tur    = strrev($rut);
        $mult   = 2;
        $suma   = 0;
        	
        for ($i = 1; $i <= strlen($tur); $i++) {
            if ($mult > 7) {
                $mult = 2;
            }
            $suma = $mult * substr($tur, $i,1) + $suma;
            $mult = $mult + 1;
            
        }
        echo 'suma:' . $suma;
        echo $valor = 11 - $suma % 11;
        
        if ($valor == 11) {
            $codigo_veri = "0";
        } elseif ($valor == 10) {
            $codigo_veri = "k";
        } else {
            $codigo_veri = $valor;
        }
    }
    
    
    
    
}

//RUT::verificar('211003420017');
echo RUT::ver2('210193770016');
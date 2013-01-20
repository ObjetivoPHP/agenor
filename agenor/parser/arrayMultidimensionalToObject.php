<?php
namespace agenor\parser;

/**
 * Clase arrayMultidimensionalToObject.
 * Convierte un arreglo multidimensional en un objeto.
 * Ej.: Convierte un arreglo del tipo
 * array(1) {
 * ["vista"]=>
 * array(3) {
 *   ["local"]=>
 *   array(2) {
 *     ["layout"]=>
 *     string(10) "layout.php"
 *     ["main"]=>
 *     string(8) "main.php"
 *   }
 *   ["server"]=>
 *   array(2) {
 *     ["layout"]=>
 *     string(12) "lyserver.php"
 *     ["main"]=>
 *     string(12) "mnserver.php"
 *   }
 *  ["unknow"]=>
 *   string(11) "desconocido"
 * }
 *}
 * 
 * para poder ser accedido de la forma $objeto->vista->local->layout que retornara
 *  layout.php.
 * El objeto retornado es del tipo:
 * object(stdClass)#5 (1) {
 * ["vista"]=>
 * object(stdClass)#4 (3) {
 *   ["local"]=>
 *   object(stdClass)#2 (2) {
 *     ["layout"]=>
 *     string(10) "layout.php"
 *     ["main"]=>
 *     string(8) "main.php"
 *   }
 *   ["server"]=>
 *   object(stdClass)#3 (2) {
 *     ["layout"]=>
 *     string(12) "lyserver.php"
 *     ["main"]=>
 *     string(12) "mnserver.php"
 *   }
 *   ["unknow"]=>
 *   string(11) "desconocido"
 * }
 *}
 *
 * @package     parser creado en el projecto agenor
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (21/05/2012 - 21/05/2012)
 */
class arrayMultidimensionalToObject
{
    /**
     * Convierte un arreglo en una stdClass.
     * @param   array   $arreglo    Arreglo que se quiere convertir en una stdClass (objeto).
     * @return  Object (stdClass)
     */
    public static function convert(array $arreglo)
    {
        foreach($arreglo as $clave => $valor) {
            if(is_array($valor)) {
                $arreglo[$clave] = self::convert($valor);
            }
        }
        return (object) $arreglo;
    }
    
    /**
     * Convierte un Arreglo en una StdClass
     * @param array $array      Arreglo que se quiere convertir en una stdClass (Objeto)
     * @return Object
     */
    public static function convert2(array $array)
    {
        if (is_array($array)) {
            return (object) array_map(__FUNCTION__, $array);
	} else {
            return $array;
        }
    }
}


<?php
namespace agenor\mvc;

/**
 * Clase Debug.
 * Contiene los metodos especiales para correccion de codigo.
 *
 * @package     MVC creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.0.0 (11/09/2012 - 11/09/2012)
 */
final class Debug
{
    /**
     * Lanza por pantalla el contenido de una variable u Objeto.
     * 
     * @param   mixed   $variable   Variable de la cual se quiere ver su contenido.
     * @param   string  $nombre     Nombre de la Variable que se desplegara su contenido
     * @return  void    Salida por Pantalla.
     */
    public static function variable($variable, $nombre = '')
    {
        if (Registry::get('environment') == 'debug' ) {
            echo '<pre>';
            echo '##################### INI ZONA DEBUG #####################' . "\n";
            echo $nombre . ' : ';
            var_dump($variable);
            echo '##################### FIN ZONA DEBUG #####################' . "\n";
            echo '</pre>';
        }
    }
    

    
    
    
}
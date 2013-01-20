<?php
namespace agenor\mvc;

/**
 * Clase Plugin.
 * Interface que deberan implementar los plugins del sistema.
 * Que se ejecutaran antes del metodo init.
 * Se deberan incluir en el directorio plugins, con namespace plugins.
 *
 * @package     MVC creado en el projecto AGENOR
 * @copyright   2013 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (01/01/2013 - 01/01/2013)
 */
interface Plugin
{
    /**
     * Metodo Abstracto que se ejecutara.
     */
    public static function execute();
    
}

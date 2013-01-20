<?php
namespace agenor\form\buttons;

/**
 * Clase Reset.
 * Contiene un boton tipo Reset
 *
 * @package     Form creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (09/10/2012 - 09/10/2012)
 */
class Reset extends \agenor\form\ButtonAbstract
{
    
    public function __construct($nombre, $value, $id = '')
    {
        parent::__construct('reset', $nombre, $value, $id);
        return $this;
    }
}

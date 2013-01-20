<?php
namespace agenor\helpers;

/**
 * Clase Js.
 *  Se encarga de crear un Helper para insertar un archivo Js.
 *
 * @package     MVC creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (23/09/2012 - 23/09/2012)
 */
class Js extends Helpers
{
    private $_file;

    public function file($archivo)
    {
        $this->_file    = $archivo .'.js';
        return $this;
    }
    
   
    public function render()
    {   
        $archivo = $this->_dirFile . '/js/' . $this->_file;
        return '<script type="text/javascript" src="' . $archivo . '"></script>' . "\n";
    }
}


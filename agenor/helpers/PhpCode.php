<?php
namespace agenor\helpers;

/**
 * Clase Css.
 *  Se encarga de crear un Helper para insertar una css.
 *
 * @package     MVC creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (23/09/2012 - 23/09/2012)
 */
class PhpCode extends Helpers
{

    private $_clase = '';
    
    private $_codigo = '';
    
    public function classDiv($clase)
    {
        $this->_clase = $clase;
        
        return $this;
    }
    
    public function codigo($codigo)
    {
        $this->_codigo = $codigo;
        
        return $this;
    }


    
    public function  render()
    {
        $codigo = '<div class="' . $this->_clase . '" >'
                . $this->_codigo
                . '</div>' . "\n";
        
        return $codigo;
    }
    
}


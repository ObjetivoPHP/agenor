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
class Css extends Helpers
{


    private $_file;

    private $_media = '';

    public function file($archivo)
    {
        $this->_file    = $archivo .'.css';
        return $this;
    }
    
    public function media($media)
    {
        $this->_media = $media;
        return $this;
    }
    
    public function  render()
    {
        
        $archivo = $this->_dirFile . '/css/' . $this->_file;
        if ($this->_media) {
            $this->_media = 'media="' . $this->_media . '"';
        }
        return '<link rel="stylesheet" href="' . $archivo . '" ' . $this->_media . ' >' . "\n";
    }
    
}


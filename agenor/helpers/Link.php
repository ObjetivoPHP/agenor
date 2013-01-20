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
class Link extends Helpers
{


    private $_file  = URL_BASE ;

    private $_text  = '';
    
    private $_clase = '';
    
    private $_http  = 'http://';

    private $_target = '';
    
    private $_img = null;

    public function page($archivo)
    {
        $this->_file = $this->_file . $archivo ;
        return $this;
    }
    
    public function text($texto)
    {
        $this->_text = $texto;
        return $this;
    }
    
    public function cssClass($class)
    {
        $this->_clase = $class;
        return $this;
    }
    
    public function https()
    {
        $this->_http = 'https://';
        return $this;
    }
    
    public function http()
    {
        $this->_http = 'http://';
        return $this;
    }
    
    public function img(\agenor\helpers\Img $img)
    {
        $this->_img = $img;
        return $this;     
    }
    
    
    public function target($target)
    {
        // _blank, _top, _parent, _self
        switch ($target) {
            case '_blank':
                $this->_target = '_blank';
                break;
            case '_top';
                $this->_target = '_top';
                break;
            case '_parent':
                $this->_target = '_parent';
                break;
            case '_self':
                $this->_target = '_self';
            default:
                $this->_target = '';
                break;
        }
        return $this;
    }
    
    public function  render()
    {
        $target = ($this->_target)? ' target="' . $this->_target . '" ' : '';
        $clase  = ($this->_clase)? ' class="' . $this->_clase . '" ' : '';
        
        return '<a href="' . $this->_http . $this->_file . '" ' . $target . $clase . '>' . $this->_text . '</a>' . "\n";
    }
    
}


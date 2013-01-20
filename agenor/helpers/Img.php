<?php
namespace agenor\helpers;

/**
 * Clase Img.
 * Se encarga de crear un Helper para insertar un campo Tipo input.
 *
 * @package     MVC creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (15/10/2012 - 15/10/2012)
 */
class Img extends \agenor\helpers\Helpers
{
    
    private $_src;
    private $_alt;
    private $_height;
    private $_width;
    private $_usemap;
    private $_ismap;
    private $_value;
    
    
    public function src($url)
    {
        $this->_src = ($url)? ' src="' . $url . '"' : '';
        return $this;
    }

    public function alt($alt)
    {
        $this->_alt = ($alt)? ' alt="' . $alt . '"' : '';
        return $this;
    }
    
    public function height($altura)
    {
        if (is_int($altura)) {
            $this->_height = ' height="' . $altura . '"';
        } else {
            $this->_height = '';
        }
        
        return $this;
    }
    
    public function width($ancho)
    {
        if (is_int($ancho)) {
            $this->_width = ' width="' . $ancho . '"';
        } else {
            $this->_width = '';
        }
        
        return $this;
    }
    
    /**
     * <img src="planets.gif" width="145" height="126" alt="Planets" usemap="#planetmap">
     * <map name="planetmap">
  <area shape="rect" coords="0,0,82,126" href="sun.htm" alt="Sun">
  <area shape="circle" coords="90,58,3" href="mercur.htm" alt="Mercury">
  <area shape="circle" coords="124,58,8" href="venus.htm" alt="Venus">
</map> 
     * Mapa gestionado en el cliente
     * @param type $usemap
     * @return \agenor\helpers\Img
     */
    public function usemap($usemap)
    {
        $this->_usemap =  ($usemap)? ' usemap="#' . $usemap .'"' : '';
        return $this;
    }
    
    /**
     * ismap="ismap" para mapas gestionados en el servidor
     * <a href="http://www/cgi-bin/imagemap><img ismap src="imagen.gif" /></a>
     * @param type $map
     * @return \agenor\helpers\Img
     */
    public function ismap($map)
    {
        $this->_ismap = ($map)? ' ismap="' . $map . '"' : '';
        return $this;
    }

    
    public function render()
    {
        $a  = '<img';
        $a .= $this->_id;
        $a .= $this->_class;
        $a .= $this->_alt;
        $a .= $this->_height;
        $a .= $this->_ismap;
        $a .= $this->_src;
        $a .= $this->_usemap;
        $a .= $this->_value;
        $a .= $this->_width;
        $a .= ' />';
        
        return $a;
    }
    
}

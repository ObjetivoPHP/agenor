<?php
namespace agenor\helpers;

/**
 * Clase Label.
 * Se encarga de crear un Helper para insertar un campo Tipo input.
 *
 * @package     MVC creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (08/10/2012 - 08/10/2012)
 */
class Label extends \agenor\helpers\Helpers
{
    /**
     * Establece el Campo para el Cual es la etiqueta.
     * @var string 
     */
    private $_for;
    
    /**
     * Texto de la etiqueta.
     * @var string
     */
    private $_text;
    
    /**
     * Establece si la etiqueta Label, contendra al campo input o estara al costado.
     * true : <label><input></lablel>
     * false: <label></lablel><input>
     * @var boolean 
     */
    private $_fieldContent = false;
    
    
    private $_class;


    public function setFor($for)
    {
        $this->_for = $for;
        return $this;
    }

    public function setText($text)
    {
        $this->_text = $text;
        return $this;
    }

    public function setFieldContent($fieldContent)
    {
        $this->_fieldContent = $fieldContent;
        return $this;
    }


    public function setClass($class)
    {
        $this->_class = $class;
        return $this;
    }
    
    public function render()
    {
        //<label for='campo' class="clase">Contenido</label>
        
        $label  = '<label for="' . $this->_for .'"';
        if ($this->_class) {
            $label .= ' class="' . $this->_class .'"';
        }
        $label .= '>' . $this->_text;
        
        if ($this->_fieldContent) {
            $label .= '%s</label>' . "\n";
        } else {
            $label .= '</label>%s' . "\n";
        }

        return $label;
    }



}

<?php
namespace agenor\helpers;

/**
 * Clase Input.
 * Se encarga de crear un Helper para insertar un campo Tipo input.
 *
 * @package     MVC creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (06/10/2012 - 06/10/2012)
 */
class Input extends \agenor\helpers\Helpers
{
    private $_name;
    private $_id;
    private $_label;
    private $_required      = '';
    private $_value;
    private $_placeHolder;
    private $_disabled      = false;
    private $_size;
    private $_maxlength;
    private $_pattern;
    private $_type          = ' type="text" ';
    private $_autoComplete;
    private $_autoFocus     = false;
    private $_spellcheck    = false;
    
    private $_listId        = '';
    private $_class         = '';


    private $_min;
    private $_max;
    private $_step;




    public function setName($name)
    {
        $this->_name = ' name="' . $name . '" ';
        return $this;
    }

    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    public function setLabel(\agenor\helpers\Label $label)
    {
        $this->_label = $label;
        return $this;
    }

    public function setRequired($required = false)
    {
        if ($required) {
            $this->_required = ' required ';
        } else {
            $this->_required = '';
        }
        return $this; 
    }

    public function setValue($value)
    {
        if ($value) {
            $this->_value = ' value="' . $value . '" ';
        } else {
            $this->_value = '';
        }
        
        return $this;
    }

    public function setPlaceHolder($placeHolder)
    {
        if ($placeHolder) {
            $this->_placeHolder = ' placeholder="' . $placeHolder . '" ';
        } else {
            $this->_placeHolder = '';
        }
        
        return $this;
    }

    public function setDisabled($disabled = false)
    {
        if ($disabled) {
            $this->_disabled = ' disabled ';
        } else {
            $this->_disabled = '';
        }
            
        return $this;
    }

    public function setSize($size)
    {
        if ($size) {
            $this->_size = ' size="' . $size . '" ';
        } else {
            $this->_size = '';
        }
        
        return $this;
    }

    public function setMaxlength($maxlength)
    {
        if ($maxlength) {
            $this->_maxlength = ' maxlength="' . $maxlength . '" ';
        } else {
            $this->_maxlength = '';
        }
        
        return $this;
    }

    public function setPattern($pattern)
    {
        if ($pattern) {
            $this->_pattern = ' pattern="' . $pattern . '" ';
        } else {
            $this->_pattern = '';
        }
        return $this;
    }

    public function setType($type)
    {
        $this->_type = ($type)? $type : 'text';
        $this->_type = ' type="' . $type . '" ';
        if ($type) {
            $this->_type = ' type="' . $type . '" ';
        } else {
            $this->_type = ' type="text" ';
        }
        
        return $this;
    }

    public function setAutoComplete($autoComplete)
    {
        if ($autoComplete) {
            $this->_autoComplete = ' autocomplete="on" ';
        } else {
            $this->_autoComplete = ' autocomplete="off" ';
        }
        
        return $this;
    }

    public function setAutoFocus($autoFocus)
    {
        $this->_autoFocus = ($autoFocus)? ' autofocus ' : '';
        
        return $this;
    }

    public function setSpellcheck($spellcheck)
    {
        if ($spellcheck) {
            $this->_spellcheck = ' spellcheck="true" ';
        } else {
            $this->_spellcheck = ' spellcheck="false" ';
        }
        
        return $this;
    }

    public function setMin($min)
    {
        $this->_min = ($min)? ' min="' . $min .'" '  : '';
        
        return $this;
    }

    public function setMax($max)
    {
        $this->_max = ($max)? ' max="' . $max .'" '  : '';
        
        return $this;
    }

    public function setStep($step)
    {
        $this->_step = ($step)? ' step="' . $step .'" '  : '';
        return $this;
    }
    
    public function setListId($listId)
    {
        $this->_listId = ($listId)? ' list="' . $listId .'" '  : '';
        return $this;
    }

    public function setClass($class)
    {
        $this->_class = ($class)? ' class="' . $class .'" '  : '';
        return $this;
    }
        
    public function render()
    {
        $input  = '<input id="' . $this->_id . '" '
                . $this->_name
                . $this->_type
                . $this->_autoComplete
                . $this->_autoFocus
                . $this->_disabled
                . $this->_max
                . $this->_maxlength
                . $this->_min
                . $this->_pattern
                . $this->_placeHolder
                . $this->_required
                . $this->_size
                . $this->_spellcheck
                . $this->_step
                . $this->_listId
                . $this->_class
                . $this->_value . ' >' ."\n";
        
        if ($this->_label instanceof \agenor\helpers\Label) {
            $label  = $this->_label->render();
            $input  = sprintf($label, $input);
        }

        $input = str_replace("  ", " ", $input);
        return $input;
    }
}

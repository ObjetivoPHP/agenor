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
class Submit extends \agenor\form\ButtonAbstract
{
  
    protected $_formAction;
    protected $_formEnctype;
    protected $_formMethod;
    protected $_formNoValidate;
    protected $_formTarget;
    

    public function __construct($nombre, $value, $id = '')
    {
        parent::__construct('submit', $nombre, $value, $id);
        return $this;
    }
    
    
    public function setFormAction($formAction)
    {
        $this->_formAction = $formAction;
        return $this;
    }

    public function setFormEnctype($formEnctype)
    {
        $this->_formEnctype = $formEnctype;
        return $this;
    }

    public function setFormMethod($formMethod)
    {
        $this->_formMethod = $formMethod;
        return $this;
    }

    public function setFormNoValidate($formNoValidate)
    {
        $this->_formNoValidate = $formNoValidate;
        return $this;
    }

    public function setFormTarget($formTarget)
    {
        $this->_formTarget = $formTarget;
        return $this;
    }

    /**
     * Renderiza un campo tipo Button.
     * @return string
     */
    public function render()
    {
        $boton  = '<button type="' . $this->_type . '" value="' . $this->_value . '"';
        if ($this->_form) {
            $boton .= ' form="' . $this->_form . '" ';
        }
        if ($this->_name) {
            $boton .= ' name="' . $this->_name. '" ';
        }
        if ($this->_autoFocus) {
            $boton .= ' autofocus="' . $this->_autoFocus. '" ';
        }
        if ($this->_disabled) {
            $boton .= ' disabled ';
        }
        if ($this->getClass()) {
            $boton .= 'class="' . $this->getClass() . '" ';
        }
        if ($this->_id) {
            $boton .= 'id="' . $this->_id . '" ';
        }
        // Acciones de Formulario en Botones
        if ($this->_formAction) {
            $boton .= 'formaction="' . $this->_formAction . '" ';
        }
        if ($this->_formEnctype) {
            $boton .= 'formenctype="' . $this->_formEnctype . '" ';
        }        
        if ($this->_formMethod) {
            $boton .= 'formmethod="' . $this->_formMethod . '" ';
        }     
        if ($this->_formNoValidate) {
            $boton .= 'formnovalidate="' . $this->_formNoValidate . '" ';
        }
        if ($this->_formTarget) {
            $boton .= 'formtarget="' . $this->_formTarget . '" ';
        }        
        
        $boton .= '>' . $this->_value . '</button>' . "\n";
        $boton  = str_replace("  ", " ", $boton);
        return $boton;
    }
 
}

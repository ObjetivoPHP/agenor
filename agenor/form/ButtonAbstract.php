<?php
namespace agenor\form;

/**
 * Clase ButtonAbstract.
 * Contiene lo Basico para los Botones de un Formulario.
 *
 * @package     Form creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (08/10/2012 - 08/10/2012)
 */
abstract class ButtonAbstract extends \agenor\form\ElementAbstract
{
    /**
     * Contiene el identificador el Boton.
     * @var string 
     */
    protected $_id;

    /**
     * Propiedad Type del Boton (button,reset,submit)
     * @var string 
     */
    protected $_type;
    
    /**
     * Propiedad Value del Boton.
     * @var string 
     */
    protected $_value;
    
    /**
     * Contiene el identificador de Formulario al cual pertenece el Boton.
     * @var string
     */
    protected $_form;
    
    
    public function __construct($tipo, $nombre, $value, $id= '')
    {
        $this->_value  = $value;
        $this->_name   = $nombre;
        $this->_id     = $id;
        $this->setType($tipo);
        return $this;
    }
    
    
    /**
     * Configura el Tipo de Boton que se generara.
     * @param string $tipo Tipo de Boton (button,submit,reset)
     * @return \agenor\form\ButtonAbstract
     */
    public function setType($tipo)
    {
        switch ($tipo) {
            case 'button':
                $this->_type = 'button';
                break;
            case 'submit':
                $this->_type = 'submit';
                break;
            case 'reset':
                $this->_type = 'reset';
                break;
            default:
                $this->_type = 'submit';
                break;
        }
        
        return $this;
    }
    
    /**
     * Configura el Valor o texto del Boton.
     * @param string $valor Texto del boton.
     * @return \agenor\form\ButtonAbstract
     */
    public function setValue($valor)
    {
        $this->_value = $valor;
        return $this;
    }
    
    /**
     * Configura a que Formulario esta asociado el Boton.
     * @param \agenor\form\agenor\form\Form $form Objeto de Tipo Formulario con el cual se asociara.
     * @return \agenor\form\ButtonAbstract
     */
    public function setForm(agenor\form\Form $form)
    {
        $this->_form = $form->getId();
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
        
        $boton .= '>' . $this->_value . '</button>' . "\n";
        $boton  = str_replace("  ", " ", $boton);
        return $boton;
    }
}

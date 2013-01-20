<?php
namespace agenor\form\fields;

/**
 * Clase DateTime.
 *
 * @package     Form creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (08/10/2012 - 08/10/2012)
 */
class Date extends \agenor\form\FieldAbstract implements \agenor\form\FormValidateInterface
{
    protected $_type = 'date';
    
    /**
     * Constructor de Campo Tipo DateTime.
     * @param string    $nombre Nombre del Campo
     * @param string    $id     Identificador del Campo
     * @return \agenor\form\Text Campo de Tipo Texto
     */
    public function __construct($nombre, $id = '')
    {
        parent::__construct('date', $nombre, $id);
        return $this;
    }   
    
    /* Atributos no permitidos para el Campo */
    public function setMaxlength($maxlength)            { return $this; }
    public function setDirname($dirname)                { return $this; }
    public function setPattern($pattern)                { return $this; }  
    public function setSpellcheck($spellcheck = false)  { return $this; }
    public function setMultiple($multiple = false)      { return $this; }

    /**      
     * Renderiza un Campo Input tipo TEXT
     * @link    http://dev.w3.org/html5/markup/input.text.html#input.text
     * @see     name|disabled|form|type|autocomplete|autofocus|list|min|max|step
     *          readonly|required|value
     *          class | autofocus | name | disabled estos son genericos a todos
     * @return string
     */    
    public function render()
    {
        return sprintf(parent::render(),'');
    }

    public function validate($valor)
    {   
        $retorno = false;
        $fecha  = explode('-', $valor);
        if (count($fecha) == 3) {
            $fecha[0]   = (int) $fecha[0];
            $fecha[1]   = (int) $fecha[1];
            $fecha[2]   = (int) $fecha[2];
            $max        = (isset($this->_max))? $this->_max : $fecha;
            $min         = (isset($this->_min))? $this->_min : $fecha;
            if (!checkdate($fecha[1], $fecha[2], $fecha[0]) || !($valor > $max || $valor < $min)) {
                $this->_error = "Fecha no valida";
            } else {
                $retorno = true;
            }
        } else {
            $this->_error = "Fecha no valida";
        }
        return $retorno;
    }
}

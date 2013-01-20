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
class Datetime extends \agenor\form\FieldAbstract implements \agenor\form\FormValidateInterface
{
    protected $_type = 'datetime';
    
    /**
     * Constructor de Campo Tipo DateTime.
     * @param string    $nombre Nombre del Campo
     * @param string    $id     Identificador del Campo
     * @return \agenor\form\Text Campo de Tipo Texto
     */
    public function __construct($nombre, $id = '')
    {
        parent::__construct('datetime', $nombre, $id);
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
        $retorno = true;
        /*$fecha  = explode('-', $valor);
        if (!checkdate($fecha[1], $fecha[2], $fecha[0])) {
            $this->_error = "Fecha no valida";
        } else {
            $retorno = true;
        }*/
        
        return $retorno;
    }
    
    function isValidDateTime($dateTime)
{
    if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $dateTime, $matches)) {
        if (checkdate($matches[2], $matches[3], $matches[1])) {
            return true;
        }
    }

    return false;
} 
    
}

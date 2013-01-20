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
class Time extends \agenor\form\FieldAbstract implements \agenor\form\FormValidateInterface
{
    protected $_type = 'time';
    
    /**
     * Constructor de Campo Tipo DateTime.
     * @param string    $nombre Nombre del Campo
     * @param string    $id     Identificador del Campo
     * @return \agenor\form\Text Campo de Tipo Texto
     */
    public function __construct($nombre, $id = '')
    {
        parent::__construct('time', $nombre, $id);
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
        $retorno    = false;
        $tiempo     = explode(':', $valor);
        if (count($tiempo) == 2) {
            $tiempo[0]  = (int) $tiempo[0];
            $tiempo[1]  = (int) $tiempo[1];

            if ( $tiempo[0] >= 0 && $tiempo[0] <= 23 && $tiempo[1] >=0 && $tiempo[1] <=59  ) {
                $valor  = $tiempo[0] . ':' . $tiempo[1];
                $retorno= true;
            }
        }

        if (!$retorno) {
            $this->_error = "La hora no es Valida.";
        }

        return $retorno;
    }
}

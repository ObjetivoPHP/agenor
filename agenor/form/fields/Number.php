<?php
namespace agenor\form\fields;

/**
 * Clase Text.
 * Generador de Formulario en base a un arreglo de Datos.
 *
 * @package     Form creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (06/10/2012 - 06/10/2012)
 */
class Number extends \agenor\form\FieldAbstract implements \agenor\form\FormValidateInterface
{
    protected $_type = 'number';
    
    /**
     * Constructor de Campo Tipo Number.
     * @param string    $nombre
     * @param string    $id
     * @return \agenor\form\Number Campo de Tipo Texto
     */
    public function __construct($nombre, $id = '')
    {
        parent::__construct('number', $nombre, $id);
        return $this;
    }
    
    /* Atributos no permitidos para el Campo */
    public function setMultiple($multiple = false)      { return $this; }
   
    /**      
     * Renderiza un Campo Input tipo Number
     * @link    http://dev.w3.org/html5/markup/input.text.html#input.text
     * @see     name|id|form|type|disabled||maxlength|readonly|size|value|autocomplete
     *          autofocus|list|pattern|required|placeholder|dirname
     *          class | autofocus | name | disabled estos son genericos a todos
     * @return string
     */
    public function render()
    {
        return sprintf(parent::render(), '');
    }

    /**
     * Retorna si el Campo se Valido Correctamente o no.
     * @param   mixed   $valor  Valor que se quiere saber si es Valido
     * @return  boolean         true/false
     */
    public function validate($valor)
    {
        $retorno = true;
        if (!is_numeric($valor)) {
            $this->_error = 'No contiene un numero.';
            $retorno = false;
        }
        return $retorno;
    } 
}
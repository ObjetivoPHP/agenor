<?php
namespace agenor\form\fields;

/**
 * Clase Email.
 * Generador de Formulario en base a un arreglo de Datos.
 *
 * @package     Form creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (06/10/2012 - 06/10/2012)
 */
class Email extends \agenor\form\FieldAbstract implements \agenor\form\FormValidateInterface
{
    protected $_type = 'email';
    
    /**
     * Constructor de Campo Tipo Text.
     * @param string    $nombre
     * @param string    $id
     * @return \agenor\form\Text Campo de Tipo Texto
     */
    public function __construct($nombre, $id = '')
    {
        parent::__construct('email', $nombre, $id);
        return $this;
    }
    
    /* Atributos no permitidos para el Campo */
    public function setDirname($dirname)                { return $this; }
    public function setMax($max)                        { return $this; }
    public function setMin($min)                        { return $this; }
    public function setStep($step)                      { return $this; }
    public function setSpellcheck($spellcheck = false)  { return $this; }

    /**      
     * Renderiza un Campo Input tipo TEXT
     * @link    http://dev.w3.org/html5/markup/input.text.html#input.text
     * @see     name|id|form|type|disabled||maxlength|readonly|size|value|autocomplete
     *          autofocus|list|pattern|required|placeholder|dirname
     *          class | autofocus | name | disabled estos son genericos a todos
     * @return string
     */
    public function render()
    {
        $addOptions = ($this->_multiple)? ' multiple' : '';
        return sprintf(parent::render(), $addOptions);
    }

    /**
     * Retorna si el Campo se Valido Correctamente o no.
     * @param   mixed   $valor  Valor que se quiere saber si es Valido
     * @return  boolean         true/false
     */
    public function validate($valor)
    {
        $validado   = false;
        if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
            $this->_error = 'El Texto no es un Correo Valido';
        } else {
            $validado = true;
        }
        
        return $validado;
    } 
}

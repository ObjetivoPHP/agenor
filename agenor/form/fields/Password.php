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
class Password extends \agenor\form\FieldAbstract implements \agenor\form\FormValidateInterface
{
    protected $_type = 'password';
    
    /**
     * Constructor de Campo Tipo DateTime.
     * @param string    $nombre Nombre del Campo
     * @param string    $id     Identificador del Campo
     * @return \agenor\form\Text Campo de Tipo Texto
     */
    public function __construct($nombre, $id = '')
    {
        parent::__construct('password', $nombre, $id);
        return $this;
    }   
    
    /* Atributos no permitidos para el Campo */
    public function setMin($min)                        { return $this; }
    public function setDirname($dirname)                { return $this; }  
    public function setSpellcheck($spellcheck = false)  { return $this; }
    public function setMultiple($multiple = false)      { return $this; }
    public function setStep($step)                      { return $this; }
    public function setListId($listId)                  { return $this; }
    public function setMax($max)                        { return $this; }

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
        return true;
    }
}

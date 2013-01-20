<?php
namespace agenor\form;

/**
 * Clase ElementAbstract.
 * Contiene lo Basico para los elementos de un Formulario.
 *
 * @package     Form creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (08/10/2012 - 08/10/2012)
 */
abstract class ElementAbstract
{
    /**
     * Clase de estilo para la etiqueta label.
     * @var string
     */
    protected $_class;
    
    /**
     * Especifica si el Campo Tendra el Foco o no.
     * @var Boolean
     */
    protected $_autoFocus = false;
    
    /**
     * Nombre del Elemento de Formulario. Propiedad name.
     * @var string
     */
    protected $_name;

    /**
     * Establece si el Campo Aparecera desactivado o no.
     * @var boolean 
     */
    protected $_disabled = false;
    
    /**
     * Configura la clase de estilo para el campo.
     * @param   string  $clase  Nombre de la clase de estilo.
     * @return \agenor\form\Label
     */
    public function setClass($clase)
    {
        $this->_class = $clase;
        return $this;
    }
    
    /**
     * Retorna la clase de estilo para el elemento.
     * @return string
     */
    public function getClass()
    {
        return $this->_class;
    }
    
    /**
     * Establece la Propiedad name de un elemento de formulario.
     * @param string    $nombre nombre del elemento.
     */
    public function setName($nombre)
    {
        $this->_name = $nombre;
        return $this;
    }
    
    /**
     *  Retorna el Valor de la propiedad name del elemento.
     * @return string   Nombre del elemento.
     */
    public function getName()
    {
        return $this->_name;
    }
    
    /**
     * VER DE PASAR A FORM.############################S
     * Establece si el campo tendra el foco o no. 
     * @param boolean $autoFocus
     */
    public final function setAutoFocus($autoFocus = false)
    {
        $this->_autoFocus= ($autoFocus)? true : false;
        return $this;
    }
    
    /**
     * Establece si el campo esta desabilitado o no.
     * @param boolean $disabled true/false
     */
    public final function setDisabled($disabled = false)
    {
        $this->_disabled = ($disabled)? true : false;
        return $this;
    }    
    
    /**
     * Envia a renderizar el elemento
     * @return  string
     */
    public function __toString()
    {
        return $this->render();
    }
    
    /**
     * Todas las clases que hereden deberan implementar el metodo
     * render, que genera el codigo HTML del tipo del elemento.
     */
    abstract public function render();
}

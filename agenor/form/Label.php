<?php
namespace agenor\form;

/**
 * Clase Label.
 * Genera una Etiqueta Label.
 *
 * @package     Form creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (07/10/2012 - 07/10/2012)
 */
class Label extends \agenor\form\ElementAbstract
{
    /**
     * Establece el Campo para el Cual es la etiqueta.
     * @var string 
     */
    private $_for;
    
    /**
     * Texto de la etiqueta.
     * @var string
     */
    private $_text;
    
    /**
     * Establece si la etiqueta Label, contendra al campo input o estara al costado.
     * true : <label><input></lablel>
     * false: <label></lablel><input>
     * @var boolean 
     */
    private $_fielContent = false;

    /**
     * Constructor de Etiqueta.
     * La misma debera estar contenida en un Campo del Formulario.
     * 
     * @param   string      $campo      Nombre del Campo al cual se relaciona.
     * @param   string      $texto      Texto que se mostrara.
     * @param   boolean     $contiene   Si esta envuelve o no a la etiqueta input.
     * @param   string      $clase      Nombre de la clase de estilo.
     * @return \agenor\form\Label
     */
    public function __construct($campo, $texto = '', $contiene = false, $clase = '' )
    {
        $this->_for         = $campo;
        $this->_text        = ($texto)?     $texto      : $campo;
        $this->_fielContent = ($contiene)?  $contiene   : false;
        $this->_class       = ($clase)?     $clase      : '';
        
        return $this;
    }
    
    /**
     * Configura el Texto de la etiqueta label.
     * @param   string  $texto  Texto para la etiqueta label.
     * @return \agenor\form\Label
     */
    public function setText($texto)
    {
        $this->_text = $texto;
        return $this;
    }
    
    public function getText()
    {
        return $this->_text;
    }
    
    /**
     * Establece si la etiqueta label envuelve o no a la etiqueta input.
     * true : <label><input></lablel>
     * false: <label></lablel><input>
     * @param   boolean $contenido
     * @return \agenor\form\Label
     */
    public function setFieldContent($contenido)
    {
        $this->_fielContent = ($contenido)? true : false;
        return $this;
    }
    
    
    public function render()
    {
        $label      = '<label ';
        $label     .= ' for="' . $this->_for . '"';
        $label     .= ($this->_class)?  ' class="' . $this->_class . '"' : '';
        $label     .= ' >' . $this->_text;
        if ( $this->_fielContent ) {
                $label .= '%s</label>' . "\n";
        } else {
                $label .= '</label>%s' . "\n";
        }
        
        return $label;
    }
    
   /* public function getFor()
    {
        return $this->_for;
    }

    public function getText()
    {
        return $this->_text;
    }

    public function getFielContent()
    {
        return $this->_fielContent;
    }*/


    
}
<?php
namespace agenor\form;


/**
 * Clase FieldSet.
 * Contiene un Conjunto de Campos.
 *
 * @package     Form creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (08/10/2012 - 08/10/2012)
 */
class FieldSet extends \agenor\form\ElementAbstract
{
    /**
     * Contiene los Campos que estaran en un determinado FieldSet
     * @var array(\agenor\form\Field)
     */
    private $_campos = array();
    
    /**
     * Nombre del Formulario al cual se relaciona.
     * Solo es necesario cuando esta fuera de las etiquetas FORM.
     * @var string
     */
    private $_form;
    
    /**
     * Comentario entre las etiquetas LEGEND
     * @var string 
     */
    private $_legend;
    
    /**
     *  Crea un nuevo FieldSet vacio.
     * @return \agenor\form\FieldSet
     */
    public function __construct()
    {
        return $this;
    }
    
    /**
     * Agrega un Campo al FieldSet
     * @param \agenor\form\FieldAbstract $campo Campo que se quiere Agregar.
     */
    public function addCampos(\agenor\form\ElementAbstract $campo)
    {
        $this->_campos[] = $campo;
        return $this;
    }
    
    public function getFields()
    {
        $campos = array();
        
        foreach ($this->_campos as $elemento) {
            // Si el elemento tiene la interface entonces es un campo y lo extraigo
            if ($elemento instanceof \agenor\form\FormValidateInterface) {
                $campos[] = $elemento;
            } elseif ($elemento instanceof \agenor\form\FieldSet) {
                $campos2 = $this->validate($elemento->getFields());
                $campos = array_merge($campos, $campos2);
            }  
       }

       return $campos;        
    }
    
    public function getElement($nombreBusq, $borrar = false)
    {
        $retorno = false;
        
        foreach ($this->_campos as $clave => $elemento) {
            if ($elemento instanceof \agenor\form\FieldSet) {
                $nombre = $elemento->getElement($nombreBusq, $borrar);
                if ($nombre !== false ) {
                    if (!$borrar) { return $nombre; }
                }
            } else {
                if ($nombreBusq == $elemento->getName()) {
                    if ($borrar) {
                        unset($this->_campos[$clave]);
                    } else { 
                        return $elemento;
                    }
                } 
            }
        }
        return $retorno;
    }


    
    /**
     * Agrega un Boton a un FieldSet
     * @param \agenor\form\ButtonAbstract $boton Boton que se quiere agregar.
     * @return \agenor\form\FieldSet
     */
    public function addButtons(\agenor\form\ButtonAbstract $boton)
    {
        $this->_campos[] = $boton;
        return $this;
    }
    
    /**
     * Vincula el FieldSet a un formulario.
     * Sirve para cuando el fieldset esta fuera del formulario.
     * @param string $form  Nombre el Formulario al cual se vincula.
     */
    public function setForm($form)
    {
        $this->_form = $form;
        return $this;
    }

    /**
     * Titulo o legenda para el conjunto de Campos 
     * @param string $legend Legenda para el conjunto de campos
     */
    public function setLegend($legend)
    {
        $this->_legend = $legend;
        return $this;
    }

    public function render()
    {
        $fieldSet   = '<fieldset';
        if ($this->_disabled) {
            $fieldSet .= ' disabled ';
        }
        
        if ($this->_name) {
            $fieldSet .= ' name="' . $this->_name . '" ';
        }
        
        if ($this->_form) {
            $fieldSet .= ' form="' . $this->_form . '" ';
        }
        $fieldSet   .= '>' . "\n";
        
        if ($this->_legend) {
            $fieldSet .= '<legend>' . $this->_legend . "</legend>\n";
        }
    
        // Recorro los Campos y voy renderizandolos
        foreach ($this->_campos as $campo) {
            // Ver los Decorators
            $fieldSet.= $campo->render();
        }
        // Fin Renderizado de Campos del Formulario
        
        $fieldSet  .= '</fieldset>' . "\n";
        
        return $fieldSet;
        
        
        
        
    }  
}


/**
 * <Fieldset> Envuelve un Numero de Campos
 * propiedades
 * disabled
 * name
 * form
 * 
 * <legend>
 * Contenido dentro de fieldset y da el titulo al FieldSet
 * <legent>texto<legend>
 */
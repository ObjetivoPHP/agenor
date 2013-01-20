<?php
namespace agenor\form\fields;

/**
 * Clase Datalist.
 *
 * @package     Form creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (11/10/2012 - 11/10/2012)
 */
class Select extends \agenor\form\fields\Datalist implements \agenor\form\FormValidateInterface
{
    /**
     * Cantidad de Lineas del Select
     * @var integer
     */
    protected $_size;
    
    /**
     * Establece si se puede seleccionar uno o mas elementos del select
     * @var boolean
     */
    protected $_multiple = false;
    
    /**
     * Etiqueta del Campo.
     * @var \agenor\form\Label 
     */
    protected $_label;
    
    protected $_value;
    
    protected $_error;

    public function size($size)
    {
        $this->_size = $size;
        return $this;
    }
    
    public function __construct($name, $id = '')
    {
 
        $id = ($id)? $id : $name;
        
        parent::__construct($id);
        $this->_name = $name;
        return $this;
    }
    
    public function getName()
    {
        return $this->_name;
    }
    
    public function setValue($valor)
    {
        $this->_value = $valor;
        return $this;
    }

    /**
     * Configura el valor de la propiedad label.
     * @param string $label
     */
    public function setLabel(\agenor\form\Label $label)
    {
        $this->_label = $label;
        return $this;
    }    
    
    public function setMultiple($multiple = false)
    {
        $this->_multiple = ($multiple)? true : false;
        return $this;
    }    
    
    public function getErrors()
    {
        $this->_error;
    }
    
    
    public function validate($valor)
    {
        return true;
    }
    
    public function render()
    {
        $select = '<Select';
        $select.= ' id="' . $this->_id . '"';
        $select.= ($this->_name)?       ' name="' . $this->_name . '"'          : '';
        $select.= ($this->_class)?      ' id="' . $this->_class . '"'           : '';
        $select.= ($this->_size)?       ' size="' . $this->_size . '"'          : '';
        $select.= ($this->_multiple)?   ' multiple="' . $this->_multiple . '"'  : '';
        $select.= ($this->_disabled)?   ' disabled'                             : '';
        $select.= ' >' . "\n";
      
        $opcion = ($this->_value)? $this->_value : '';
          
        foreach ($this->_options as $value => $label) {
            $seleccion = '';
            if ($label[1]) {
                $seleccion = ' selected ';
            } elseif ($this->_value == $value ) {
                $seleccion = ' selected ';
            }

            $select.= '<option value="' . $value . '" ' . $seleccion . ' >'
                      . $label[0] . '</option>' . "\n";   
        }
        $select.='</Select>' . "\n";
        
        if ($this->_label instanceof \agenor\form\Label ) {
            $label = $this->_label->render();
            $select= sprintf($label, $select);
        }
        
        return $select;
    }
    
    
}

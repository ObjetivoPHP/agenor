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
 * @version     0.1.1 (10/10/2012 - 11/10/2012)
 */
class Datalist extends \agenor\form\ElementAbstract 
{
    /**
     * Valor de la clase de estilos.
     * @var string 
     */
    protected $_class;
    
    /**
     * Identificador del DataList.
     * @var string 
     */
    protected $_id;
    
    /**
     * Arreglo de opciones del datalist.
     * @var array 
     */
    protected $_options = array();

    /**
     * Constructor del Datalist
     * @param string $id    Identificador del Campo.
     */
    public function __construct($id)
    {
        $this->_id = $id;
    }
    
    /**
     * Retorna el valor de la propiedad id del Datalist
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }
    
    /**
     * Retorna el valor de la propiedad name del Datalist
     * @return type
     */
    public function getName()
    {
        return $this->_id;
    }
    
    /**
     * Agrega un Item al conjunto de elementos del Datalist.
     * @param mixed     $value      Valor del Campo Value
     * @param mixed     $label      Valor de la etiqueta
     * @param boolean   $selected   Establece si estara seleccionado o no.
     * @return \agenor\form\fields\Datalist
     */
    public function addItem($value, $label = '', $selected = false)
    {
        $label = ($label)? $label : $value;
        $this->_options[$value] = array($label, $selected);
    }
    
    /**
     * Agrega un Rando de Opciones.
     * @param array $valores
     * @return \agenor\form\fields\Datalist
     */
    public function addRange(array $valores)
    {
        $this->_options = $this->_options + $valores;
        return $this;
    }

    /**
     * Ordena el Datalist por clave o por valor.
     * @param boolean   $clave  true : ordena por clave, false: por valor
     * @param boolean   $asc    true : ordena ascendente, false: descendente.
     */
    public function orderBy($clave = false, $asc = true)
    {
        if ($clave) {
            if ($asc) { ksort($this->_options); } else { krsort($this->_options); }
        } else {
            if ($asc) { asort($this->_options); } else { arsort($this->_options); }
        }

    }
    
    /**
     * Renderiza el elemento Datalist.
     * @return string
     */
    public function render()
    {
        $datalist = '<datalist';
        $datalist.= ' id="' . $this->_id . '"'; 
        $datalist.= ($this->_class)?    ' id="' . $this->_class . '"'   : '';
        $datalist.= ' >' . "\n";
        
        foreach ($this->_options as $value => $label) {
            $seleccion = ($label[1])? ' selected ' : '';
            $datalist.= '<option label="' . $label[0] . '" value="' . $value . '" ' . $seleccion . ' >' . "\n"; 
        }
        $datalist.='</datalist>' . "\n";
        
        return $datalist;
    }
}
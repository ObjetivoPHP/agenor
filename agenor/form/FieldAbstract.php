<?php
namespace agenor\form;

/**
 * Clase Field.
 * Contiene lo basico para los elementos input de un formulario.
 *
 * @package     Form creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.2.0 (06/10/2012 - 11/01/2013)
 */
abstract class FieldAbstract extends \agenor\form\ElementAbstract
{   
    /**
     * Tipo del Campo.
     * @var string
     */
    protected $_type;

    /**
     * Contiene el Identificador.
     * @var string 
     */
    protected $_id;
    
    /**
     * Contiene el Identificador del Formulario.
     * @var string 
     */
    protected $_form;
    
    /**
     * Etiqueta del Campo.
     * @var \agenor\form\Label 
     */
    protected $_label;
    
    /**
     * Indica si un Campo es Requerido Obligatorio o no.
     * @var boolean 
     */
    protected $_required = false;
    
    /**
     * Indica si el Campo es solo de Lectura.
     * @var type 
     */
    protected $_readOnly = false;

    /**
     * Contiene si se puede ingresar un correo o multiples correos.
     * @var boolean 
     */
    protected $_multiple = false;
    
    /**
     * Contiene el Valor por defecto del Campo.
     * @var mixed 
     */
    protected $_value;
    
    /**
     * Contiene el Valor del tributo PlaceHolder.
     * @var string 
     */
    protected $_placeHolder;
    
    /**
     * Contiene la Propiedad size.
     * @var integer
     */
    protected $_size;
    
    /**
     * Maxima cantidad de Caracteres que Acepta el Campo.
     * @var integer 
     */
    protected $_maxlength;
    
    /**
     * Representa una Expresion Regular por la cual el Campo va a ser 
     * validado.
     * @var string
     */
    protected $_pattern;
    
    /**
     * Activa o desactiva por defecto la ayuda que autocompleta.
     * @var boolean 
     */
    protected $_autoComplete = false;
    
    /**
     * Activa o Desactiva el Corrector Ortografico.
     * @var Boolean 
     */
    protected $_spellcheck = false;

    /**
     * Contiene el Mensaje de Error que se pudo haber producido.
     * @var string 
     */
    protected $_error      = '';


    /**
     * Configura el identificador del objeto datalist.
     * El elemento <datalist> y el atributo list 
     * <input type="text" list="misdatos" … >
     * <datalist id="misdatos">
     * <option label="Sr." value="Señor">
     * <option label="Sra." value="Señora">
     * <option label="Srta." value="Señorita">
     * </datalist>
     * @var string
     */
    protected $_listId = '';
    
    protected $_dirname;
    protected $_min;
    protected $_max;
    protected $_step;
    

    /**
     * Constante que define los tipos de Campos que se pueden usar.
     */
    private static $_types  = array('email', 
                                    'url', 
                                    'tel',
                                    'search',
                                    'number',
                                    'range', 
                                    'datetime',
                                    'date',
                                    'month',
                                    'week',
                                    'time',
                                    'color',
                                    'text',
                                    'submit',
                                    'button',
                                    'checkbox',
                                    'datetime-local',
                                    'file',
                                    'hidden',
                                    'image',
                                    'password',
                                    'radio',
                                    'reset',
                                    'textarea');
    
    // required placeholder size value disabled pattern="6[0-9]{2}"  maxlength id name
    // search result=5
    // number max min step
    //<input type="range" name="satisf" min="0" max="10" step="1" value="0">
    /*
     * <input type="tel" id="pais"name="pais" placeholder="+34" size="3" value="+34" disabled>
<input type="tel" id="cod" name="cod" placeholder="6**" size="3" maxlength=3 required pattern="6[0-9]{2}">
<input type="tel" id="num" name="num" placeholder="******" size="6" maxlength=6 required pattern="[0-9]{6}">
     * 
     *  El elemento <datalist> y el atributo list 
     * <input type="text" list="misdatos" … >
<datalist id="misdatos">
    <option label="Sr." value="Señor">
    <option label="Sra." value="Señora">
    <option label="Srta." value="Señorita">    
</datalist>
     */

    /**
     * Construye un Campo de Formulario del Tipo Indicado.
     * 
     * @param string    $tipo   Tipo de Campo de 
     * @param type $nombre
     * @param type $id
     * @return \agenor\form\tipo
     * @throws \Exception
     */
    public function __construct($tipo,$nombre, $id = '')
    {
        // Verifico que se Haya Definido el Nombre
        if (!isset($nombre)) {
            throw new \Exception('No se definio el Nombre de Campo');;
        }
        // Verifico el Tipo de Campo
        if(!in_array($tipo, self::$_types)) {
            throw new \Exception('Tipo de Campo no Valido');
        }
        // Termino de Configurar y retorno el Campo Creado
        $this->_name    = $nombre;
        $id             = ($id)? $id : $nombre;
        $this->_id      = $id;

        $tipo = '\agenor\form\\' . ucwords(strtolower($tipo));

        return $this;
    }
    
    /**
     * Configura el Valor para la propiedad id.
     * @param string $id
     */
    public function setId($id)
    {
        $this->_id = $id;
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
    
    public function getNameLabel()
    {
        return $this->_label->getText();
    }
    

    /**
     * Configura si el Campo va a ser Requerido o no.
     * @param boolean $required true/false
     */
    public function setRequired($required = false)
    {
        $this->_required = ($required)? true : false;
        return $this;
    }
    
    public function setReadOnly($readOnly = false)
    {
        $this->_readOnly = ($readOnly)? true : false;
        return $this;
    }
            
    
    /**
     * Configura el Valor por defecto del Campo.
     * Se recomienda sobreescribir este Metodo,
     * Para cada tipo de Campo.
     * 
     * @param mixed $value Valor del Campo
     */
    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }
    
    /**
     * Configura el Valor para texto del placeholder.
     * @param string $placeHolder   Texto a poner como ayuda en el campo.
     */
    public function setPlaceHolder($placeHolder)
    {
        $this->_placeHolder = $placeHolder;
        return $this;
    }
    
    /**
     * Establece el tamaño ancho del campo .
     * @param integer $size  tamaño (Cantidad de Caracteres de Ancho del campo).
     * @throws \Exception           Si el valor pasado no es un entero.
     */
    public function setSize($size)
    {
        $size = filter_var($size, FILTER_VALIDATE_INT);
        if ($size === false || $size < 1) {
            throw new \Exception('El Valor Size no es un Numero Entero');
        }
         
        $this->_size = $size;
        return $this;
    }

    /**
     * Establece el tamaño maximo del texto que se puede introducir en el.
     * @param integer $maxlength    Maxima cantidad de caracteres a ingresar.
     * @throws \Exception           Si el valor pasado no es un entero.
     */
    public function setMaxlength($maxlength)
    {
        $maxlength = filter_var($maxlength, FILTER_VALIDATE_INT);
        if ($maxlength === false || $maxlength < 1 ) {
            throw new \Exception('El Valor MaxLength no es un Numero Entero');
        }

        $this->_maxlength = $maxlength;
        return $this;
    }

    /**
     * Configura un patron de expresion regular.
     * @param string $pattern   Expresion regular.
     */
    public function setPattern($pattern)
    {
        $this->_pattern = $pattern;
        return $this;
    }

    /**
     * Establece si el campo da opciones de autocompletado o no.
     * @param boolean $autoComplete true:on/false:off
     */
    public function setAutoComplete($autoComplete = false)
    {
        $this->_autoComplete = ($autoComplete)? true : false;
        return $this;
    }
    
    public function setDirname($dirname)
    {
        $this->_dirname = $dirname;
        return $this;
    }

    /**
     * Habilita o desabilita la Correccion Ortografica.
     * @param boolean $spellcheck true/false
     */
    public function setSpellcheck($spellcheck = false)
    {
        $this->_spellcheck = ($spellcheck)? true : false;
        return $this;
    }
    
    public function setListId($listId)
    {
        $this->_listId = $listId;
        return $this;
    }
    
    /**
     * Establece si se pueden ingresar uno o Mas correos electronicos.
     * @param   boolean     $multiple   true(muchos)/ false(uno)
     * @return \agenor\form\fields\Email
     */
    public function setMultiple($multiple = false)
    {
        $this->_multiple = ($multiple)? true : false;
        return $this;
    }
    
    
    public function setMin($min)
    {
        $this->_min = $min;
        return $this;
    }

    public function setMax($max)
    {
        $this->_max = $max;
        return $this;
    }

    public function setStep($step) {
        $this->_step = $step;
        return $this;
    }
        
    /**
     * Retorna el Identificador del Campo
     * @return string
     */
    public function getId()
    {
        return $this->_id;
        return $this;
    }
    
    /**
     * Retorna el Ultimo mensaje de Error.
     * @return string
     */
    public function getErrors()
    {
        return $this->_error;
    }
    
    /**
     * Retorna el Valor del Campo.
     * @return mixed
     */
    public function getValue()
    {
        return $this->_value;
    }
    
    /**
     * Realiza el Renderizado de un elemento y nos deja la posibilidad de añadir
     * mas elementos.
     * @return string
     */
    public function render()
    {
        // Genero el HTML5 del Campo TEXT.
        $input  = '<input';
        $input .= ' name="' . $this->_name . '"';
        $input .= ($this->_id)?             ' id="' . $this->_id . '"'                  : '';
        $input .= ($this->_form)?           ' form="' . $this->_form . '"'              : '';
        $input .= ' type="' . $this->_type . '"';
        $input .= ($this->_disabled)?       ' disabled'                                 : '';
        $input .= ($this->_maxlength)?      ' maxlength=' . $this->_maxlength           : '';
        $input .= ($this->_readOnly)?       ' readonly'                                 : '';
        $input .= ($this->_size)?           ' size=' . $this->_size                     : '';
        $input .= ($this->_value)?          ' value="' . $this->_value . '"'            : '';
        $input .= ($this->_autoComplete)?   ' autocomplete="on"'                        : ' autocomplete="off"';
        $input .= ($this->_autoFocus)?      ' autofocus'                                : '';
        $input .= ($this->_listId)?         ' list="' . $this->_listId . '"'            : '';
        $input .= ($this->_pattern)?        ' pattern="' . $this->_pattern . '"'        : '';
        $input .= ($this->_required)?       ' required'                                 : '';
        $input .= ($this->_placeHolder)?    ' placeholder="' . $this->_placeHolder . '"': '';
        $input .= ($this->_dirname)?        ' dirname="' . $this->_dirname . '"'        : '';    // Luego de este son Genericos a Todos.
        $input .= ($this->_class)?          ' class="' . $this->_class . '"'            : '';
        $input .= ($this->_min)?            ' min="' . $this->_min . '"'                : '';
        $input .= ($this->_max)?            ' max="' . $this->_max . '"'                : '';
        $input .= ($this->_step)?           ' step="' . $this->_step . '"'              : '';
        $input .= ' %s >' . "\n";
        
        // Veo si tiene una Etiqueta o no.
        if ($this->_label instanceof \agenor\form\Label) {
            $label = $this->_label->render();
        }
        return sprintf($label, $input);        
    }
    
    public function __toString()
    {
        return $this->render();
    }

}
<?php
namespace agenor\form;

/**
 * Clase Form.
 * Generador de Formulario en base a un arreglo de Datos.
 *
 * @package     Form creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.3.0 (06/10/2012 - 15/10/2012)
 * @since       Se precindio de Usar Helpers para Poder Hacer mas portable el modulo,
 *              es decir darle independencia de los Helpers, con lo cual se podria usar
 *              totalmente por separdado.
 */
class Form extends \agenor\form\ElementAbstract
{
    /**
     * Contiene el Idioma en que se encuentra el Formulario
     * @var string 
     */
    protected $_fileIdioma = '';
    
    /**
     * Contiene el Nombre del Formulario.
     * @var string 
     */
    private $_nombre;

    /**
     * Contiene Campos o FieldSet (estos ultimos son agrupadores de Campos.)
     * @var array( mixed: Field/FieldSet) 
     */
    private $_contenedor = array();

    /**
     * Contiene el Metodo de Envio del Formulario.
     * Puede ser POST o GET.
     * @var string 
     */
    private $_metodo = "POST"; 
    
    /**
     * Contiene En donde se abrira la nueva ventana.
     * _self _blank ...
     * @var string
     */
    private $_target = "";
    
    /**
     * Contiene la pagina que Procesara el Formulario.
     * @var string
     */
    private $_action = "";
    
    /**
     * Identificador del Formulario. Propiedad id.
     * @var string 
     */
    private $_idForm;
    
    /**
     * Propiedad de Autocompletado de todos los campos del Formulario.
     * @var string (on/off) 
     */
    private $_autocomplete = 'on';

    /**
     * Establece si el Formulario se validara con las Funciones HTML5 o con
     * funciones Propias del Usuario..
     * @var Boolean 
     */
    private $_Novalidate = false;
    
    /**
     * Especifica el Tipo de Codificacion como se enviara el Formulario.
     * 1: application/x-www-form-urlencoded
     * 2 :multipart/form-data
     * 3 :text/plain
     */
    private $_enctype;
    
    /**
     * Contiene el Charset o conjunto de Caracteres del Formulario.
     * @var string 
     */
    private $_charset;
    
    
    private $_errores = array();

    
    private $_html = array();
    
    private $_condicionales = array();


    /**
     * Construye un Formulario que luego sera renderizado o validado.
     * 
     * @param string $nombre    Nombre del Formulario
     * @param string $metodo    Metodo de envio de los valores.
     * @return \agenor\form\Form Retorna un tipo de Formulario.
     */
    public function __construct($nombre = 'formulario', $metodo = 'POST')
    {
        $this->_nombre = (trim($nombre)!='')? $nombre : 'formulario';
        $this->setMetodo($metodo);
        return $this;
    }
    
    public function setLanguage($file)
    {
        $this->_fileIdioma  = ($file)? $file : '';
        
        if (file_exists($file)) {
            require_once $file;
        }
        

        return $this;
    }
    
    
    /**
     * Configura el Metodo de Envio de Los Valores.
     * @param   string  $metodo    Toma los valores POST / GET
     * @return  void
     */
    public function setMetodo($metodo = 'POST')
    {
        if ($metodo == 'GET') {
            $this->_metodo = 'GET';
        } else {
            $this->_metodo = 'POST';
        }
        return $this;
    }
    
    public function setNombre($nombre)
    {
        $this->_nombre = $nombre;
        return $this;
    }

    public function setTarget($target)
    {
        $this->_target = $target;
        return $this;
    }

    public function setAction($action)
    {
        $this->_action = $action;
        return $this;
    }

    public function setAutocomplete($autocomplete)
    {
        $this->_autocomplete = $autocomplete;
        return $this;
    }

    public function setNovalidate($Novalidate)
    {
        $this->_Novalidate = $Novalidate;
        return $this;
    }

    public function setIdForm($idForm)
    {
        $this->_idForm = $idForm;
        return $this;
    }
    
    /**
     * Define el Charset que sera usado por el Formulario
     * @param   string  $charset    Charset del Formulario
     * @return \agenor\form\Form
     */
    public function setCharset($charset)
    {
        $this->_charset = $charset;
        return $this;
    }
    
    /**
     * Especifica el Tipo de Codificacion como se enviara el Formulario.
     * 1: application/x-www-form-urlencoded
     * 2 :multipart/form-data
     * 3 :text/plain
     * @param   integer $enctype 1: application/x-www-form-urlencoded, 2 :multipart/form-data, 3 :text/plain
     * @return \agenor\form\Form
     */
    public function setEnctype($enctype)
    {
        switch ($enctype) {
            case 1:
                $this->_enctype = 'application/x-www-form-urlencoded';
                break;
            case 2:
                $this->_enctype = 'multipart/form-data';
                break;
            case 3:
                $this->_enctype = 'text/plain';
                break;
        }
        return $this;
    }
  
    /**
     * Crea un Nuevo Campo en el Formulario y lo retorna.
     * 
     * @param   string      $tipo   Tipo del Campo que se quiere Crear.
     * @param   string      $nombre Nombre del Campo.
     * @param   strig       $id     Identificador del Campo.
     * @return  \agenor\form\FieldAbstract
     * @throws \Exception   Se lanza una excepcion de campo no creado.
     */
    public function createField($tipo,$nombre, $id = '')
    {
        $class  = '\agenor\form\fields\\' . ucwords(strtolower($tipo)); 
        $campo  = new $class($nombre, $id);
        return $this->addField($campo);        
    }
    
    /**
     * Crea un nuevo FieldSet en el Formulario y lo retorna.
     * @return \agenor\form\FieldSet
     */
    public function createFieldSet()
    {
        $field = new FieldSet();
        return $this->addFieldSet($field);
    }
    
    /**
     * Agrega un Campo al Formulario.
     * @param \agenor\form\FieldAbstract $campo Objeto de tipo Field
     * @return \agenor\form\FieldAbstract       Retorna el Mismo Objeto si Fue agregado con exito.
     */
    public function addField(ElementAbstract $campo)
    {
        $this->_contenedor[] = $campo;
        return $campo;
    }
    
    /**
     * Agrega un Boton a un Formulario
     * @param \agenor\form\ButtonAbstract $boton Elemnto de Tipo Boton.
     * @return \agenor\form\ButtonAbstract
     */
    public function addButton(ButtonAbstract $boton)
    {
        $this->_contenedor[] = $boton;
        return $boton;
    }
    
    /**
     * Agega un contenedor o agrupador de Campos (FieldSet)
     * @param \agenor\form\FieldSet     $fieldSet   Agrega un Contenedor de Campos.
     * @return \agenor\form\FieldSet
     */
    public function addFieldSet(FieldSet $fieldSet)
    {
        $this->_contenedor[] = $fieldSet;
        return $fieldSet;
    }
    
    /**
     * Agrega un Datalist al Formulario.
     * @param \agenor\form\fields\Datalist $datalist    Objeto del Tipo Datalist
     */
    public function addDatalistSelect(fields\Datalist $datalist)
    {
        $this->_contenedor[$datalist->getId()] = $datalist;
        return $this;
    }
    
    /**
     * Mueve un Campo hacia un FieldSet.
     * @param   string  $campo      Nombre del Campo a Mover
     * @param   string  $conjunto   Nombre del FieldSet que sera destino del Campo
     * @return  boolean             true (exito) : false (fracaso)
     */
    public function moveFieldToFieldSet($campo, $conjunto)
    {
        $retorno    = false;
        $fieldSet   = $this->getFieldSet($conjunto);
        if ($fieldSet) {
            // Busco el Campo y se lo agrego
            $field      = $this->getFieldClave($campo);
            if ($field !== false) {
                $fieldSet->addCampos($this->_contenedor[$field]);
                unset($this->_contenedor[$field]);
                $retorno = true;
            }  
        }
        
        return $retorno;
    }
    
    /**
     * 
     * @param type $nombre
     * @return type
     */
    public function getFieldClave($nombre)
    {
        $field   = false;
        foreach ($this->_contenedor as $clave => $elemento)
        {
            if ($elemento instanceof FieldAbstract || $elemento instanceof ElementAbstract) {
                if ($elemento->getName() == $nombre ) {
                    $field  = $clave;
                    break;
                }
            }
        }
        return $field;
    }
    
    
    /**
     * Busca un elemento por su nombre y lo retorna.
     * 
     * @param   string  $nombre Nombre del Elemento que se busca
     * @return  mixed
     */
    public function getElement($nombreBusq, $borrar = false)
    {
        $retorno = false;
        
        foreach ($this->_contenedor as $clave => $elemento) {
            if ($elemento instanceof \agenor\form\FieldSet) {
                $nombre = $elemento->getElement($nombreBusq, $borrar);
                if ($nombre !== false ) {
                    if (!$borrar) { return $nombre; }
                }
            } else {
                if ($nombreBusq == $elemento->getName()) {
                    if ($borrar) { 
                        unset($this->_contenedor[$clave]);
                    } else {
                        return $elemento;
                    }
                } 
            }
        }
        if ($borrar) { $this->deleteCondicional($nombreBusq); }
        return $retorno;
    }
    
    private function deleteCondicional($campo)
    {
        foreach ($this->_condicionales as $clave => $campos) {
            if ($campo == $campos[0] || $campo == $campo[2] ) {
                unset($this->_condicionales[$clave]);
            }
        }
    }


    /**
     * Busca un elemento FieldSet dentro del Formulario por su nombre y 
     * si lo encuentra lo retorna.
     * @param   string $nombre  Nombre del FieldSet a Buscar.
     * @return \agenor\form\FieldSet o false
     */
    public function getFieldSet($nombre)
    {
        $fieldSet = false;
        foreach ($this->_contenedor as $elemento)
        {
            if ($elemento instanceof FieldSet) {
                if ($elemento->getName() == $nombre ) {
                    $fieldSet = $elemento;
                    break;
                }
            }
        }
        return $fieldSet;
    }
    
    
    public function render()
    {
        // Pasarlo a un Helper
        $form    = '<form name="' . $this->_nombre . '" method="' . $this->_metodo . '"';
        $form   .= ($this->_idForm)?    ' id="' . $this->_idForm . '" ' : '';
        $form   .= ($this->_target)?    ' target="' . $this->_target . '" ' : '';
        $form   .= ($this->_class)?     ' class="' . $this->_class  . '" ' : '';
        $form   .= ($this->_action)?    ' action="' . $this->_action . '"' : '';
        $form   .= ' >' . "\n";
        
        // Recorro los Campos y voy renderizandolos
        foreach ($this->_contenedor as $elemento) {
            // Ver los Decorators
            $form.= $elemento->render();
        }
        // Agrego HTML Adicional
        foreach ($this->_html as $html) {
            $form.= $html;
        }
        
        // Fin Renderizado de Campos del Formulario
        $form   .= '</form>' . "\n";
        
        return $form;
    }
    
    public function getErrores()
    {
        return $this->_errores;
    }
    
    
    public function setHtml($elemento)
    {
        $this->_html[] = $elemento;
    }


    public function getHtml5Errors()
    {
        $errores = '';
        foreach ($this->_errores as $error) {
            $errores .= $error . '<br>' . "\n";
        }
        return $errores;
    }
    
    /**
     * Establece condiciones entre Campos Utiles para Campos password.
     * @param   string  $campo1     Primer Campo a Comparar.
     * @param   string  $operador   Operador de Comparacion.
     *                              '=', '!=', '>' ,'>=', '<', '<=', 'contiene', 'noContiene', 'comienza', 'termina'
     * @param   string  $campo2     Segundo Campo de Condicion.
     */
    public function addCondicion($campo1, $operador, $campo2)
    {
        $operadores = array('=', '!=', '>' ,'>=', '<', '<=', 'contiene', 'noContiene', 'comienza', 'termina');
        
        if (!in_array($operador, $operadores)) {
            throw new \Exception('El Operador seleccionado no es valido');
        }
        
        if (!$this->getElement($campo1) || !$this->getElement($campo2)) {
            throw new \Exception('El campo no pertenece al Formulario.');
        }
        // Ingreso los Datos
        $this->_condicionales[] = array($campo1, $operador, $campo2);
        
        return $this;
    }
    
    
    private function _validarCondiciones(array $datos)
    {
        $validacion     = true;
        foreach ($this->_condicionales as $clave => $condicion) {
            $valCampo   = true; 
            list($campo1, $operador, $campo2) = $condicion;
            switch ($operador) {
                case '=':
                    if ($datos[$campo1] != $datos[$campo2]) {
                        $validacion = $valCampo = false;
                        $error = "Los campos %s y %s no son iguales";
                    }
                    break;
                case '!=':
                    if ($datos[$campo1] == $datos[$campo2]) {
                        $validacion = $valCampo = false;
                        $error      = "Los campos %s y %s deben ser distintos";
                    }
                    break;                   
                case '>':
                    if ($datos[$campo1] <= $datos[$campo2]) {
                        $validacion = $valCampo = false;
                        $error      = "El campo %s debe ser Mayor al campo %s";
                    }
                    break;                   
                case '>=':
                    if ($datos[$campo1] < $datos[$campo2]) {
                        $validacion = $valCampo = false;
                        $error      = "El campo %s debe ser Mayor o igual al campo %s";
                    }
                    break;                        
                case '<':
                    if ($datos[$campo1] >= $datos[$campo2]) {
                        $validacion = $valCampo = false;
                        $error      = "El campo %s debe ser Menor al campo %s";
                    }
                    break;                      
                case '<=':
                    if ($datos[$campo1] > $datos[$campo2]) {
                        $validacion = $valCampo = false;
                        $error      = "El campo %s debe ser Menor o igual al campo %s";
                    }
                    break;                  
                case 'contiene':
                    if (stripos($datos[$campo1], $datos[$campo2]) === false) {
                        $validacion = $valCampo = false;
                        $error      = "El campo %s no contiene %s";
                    }
                    break;
                case 'noContiene':
                    if (stripos($datos[$campo1], $datos[$campo2]) !== false) {
                        $validacion = $valCampo = false;
                        $error      = "El valor del campo %s no puede contener al valor del campo %s";
                    }
                    break;                    
                case 'comienza':
                    if (stripos($datos[$campo1], $datos[$campo2]) !== 0) {
                        $validacion = $valCampo = false;
                        $error      = "El valor del campo %s no comienza por el valor del %s";
                    }
                    break;                     
                case 'termina';
                    // Falta Terminar la Opcion
                    $pos = strlen($datos[$campo1]) - strlen($datos[$campo2]);

                     if (strripos($datos[$campo1], $datos[$campo2]) !== $pos) {
                        $validacion = $valCampo = false;
                        $error      = "El valor del campo %s no termina por el valor del %s";
                    }
                    break;                
            }
            if (!$valCampo) {
                $this->_errores['Condicional_' . $clave] = sprintf($error, $this->getElement($campo1)->getNameLabel(),
                                                                           $this->getElement($campo2)->getNameLabel());
            }
        }
        return $validacion;
    }
    
    public function validate(array $datos)
    { 
        $validacion = true;
        $campos     = $this->getFields();
        
        foreach ($campos as $elemento) {
            $campo      = $elemento->getName();
            if (isset($datos[$campo])) {
                if(!$elemento->validate($datos[$campo])) {
                    $validacion = false;
                    $this->_errores[$campo] = $elemento->getNameLabel() . ' ' . $elemento->getErrors();
                }
            }
        }
        
        $validCond  = $this->_validarCondiciones($datos);
        $retorno    = ($validacion && $validCond)? true : false;
        
        return $retorno;
    }
    
    private function getFields()
    {
        $campos = array();
        
        foreach ($this->_contenedor as $elemento) {
            // Si el elemento tiene la interface entonces es un campo y lo extraigo
            if ($elemento instanceof \agenor\form\FormValidateInterface) {
                $campos[] = $elemento;
            } elseif ($elemento instanceof \agenor\form\FieldSet) {
                $campos2 = $elemento->getFields();
                $campos = array_merge($campos, $campos2);
            }  
        }

        return $campos;
    }
    
    /**
     * Retorna si se enviaron datos a traves del Metodo POST.
     * @return boolean
     */
    public function isPost()
    {
        return (count($_POST))? true : false;
    }
    
    /**
     * Retorna si se enviaron datos a traves del Metodo GET.
     * @return boolean
     */
    public function isGet()
    {
        return (count($_GET))? true : false;
    }
    
    public function fillFields(array $datos)
    {
        foreach ($datos as $clave => $valor) {
            $elemento = $this->getElement($clave);
            if ($elemento) {
                $elemento->setValue($valor);
            }
            
        }
    }
    
}
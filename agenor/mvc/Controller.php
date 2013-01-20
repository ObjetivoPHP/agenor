<?php
namespace agenor\mvc;

/**
 * Clase Controller.
 * Es un Controlador Abstracto del cual deberan heredar todos los 
 * controladores del sistema. Si el controlador que hereda tiene un metodo
 * __construct debera contener como primer llamado a : parent::__construct()
 * En caso contrario, no se otorgara una vista automaticamente.
 *
 * @package     MVC creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.3.0 (10/05/2012 - 07/10/2012)
 */
abstract class Controller
{
    /**
     * Contiene un Objeto de Tipo Vista.
     * @var View 
     */
    protected $_view;
    
    /**
     * Contiene el Controlador Frontal.
     * @var FrontController
     */
    protected $_controller;
    
    /**
     * Contiene el Modelo del controlador.
     * @var Models 
     */
    protected $_model;

    /**
     * Metodo Que deberan tener todos los controladores del sistema.
     */
    abstract public function indexAction();
    
    protected $_args  = array();
    
    private $_argsAsociativos = array();
    
    protected $_rutaRelativa = '';


    /**
     * Constructor de Controlador.
     * Se encarga de levantar una vista y dejarla a disposicion.
     */
    public function __construct()
    {   
        $this->setView();
        $peticion               = Registry::get('Request');
        $this->_args            = $peticion->getArgs();
        $this->_argsAsociativos = $peticion->getArgsGet();
        $this->_rutaRelativa    = $peticion->getModulo() . '/' 
                                . $peticion->getControlador(false) . '/'
                                . $peticion->getAccion(false);
    }
    
    /**
     * Carga la vista que sera usada por el controlador.
     * @param \agenor\mvc\View $vista   Vista del Modelo Y accion que se ejecuta.
     * @return void
     */
    public function setView()
    {
        if (Registry::isResource('View')) {
            $this->_view = Registry::get('View');
        } else {
            $this->_view = new View();
        }
        
        return $this;
    }
    
    /**
     * Configura el Modelo que se usara.
     * @param type $modelo
     */
    public function setModel($modulo, $controlador)
    {
        $modeloClass        = MODULO_NAME_SPACE . $modulo . '\models\\' . $controlador . 'Models';
        $modeloPATH         = DIR_MODULO . $modulo . DS . 'models' . DS . $controlador . 'Models';

        if (file_exists(DIR_BASE . $modeloPATH . '.php')) {
            $this->_model   = new $modeloClass();
            $this->_model->setView($this->_view);
        }
    }
    
    /**
     * Carga el Controlador Frontal.
     * @param \agenor\mvc\FrontController $controlador
     */
    public function setController(FrontController $controlador)
    {
        $this->_controller = $controlador;
    }
    
    public function getController()
    {
        return $this->_controller;
    }
    
    /**
     * Retorna la Vista del controlador.
     * @return View
     */
    public function getView()
    {
        return $this->_view;
    }
    
    /**
     * Retorna los Parametros pasados por URL sanitizados
     * 
     * @param   string  $nombre     Nombre del Parametro
     * @param   mixed   $defaul     Valor a retornar en caso de que no exista el parametro.
     * @return  mixed               Valor del parametro deseado o default.
     */
    public function getArgUrl($nombre, $default = false)
    {
        return $this->getUrl($nombre, 'URL', $default);
    }
    
    public function getArgPost($nombre, $default = false)
    {
        return $this->getUrl($nombre, 'POST', $default);
    }

    /**
     * Retorna todos los elmentos que se pasaron por URL,GET,POST
     * @param   string  $tipo   Tipo es un parametro que puede ser nulo, GET,POST o URL
     * @return  array           Con los parametros solicitados
     */
    public function getAll($tipo = '')
    {
        $retorno = array();
        switch ($tipo) {
            case 'URL':
                $retorno = $this->_argsAsociativos['URL'];
                break;
            case 'GET':
                $retorno = $this->_argsAsociativos['GET'];
                break;
            case 'POST':
                $retorno = $this->_argsAsociativos['POST'];
                break;
            default:
                $retorno = $retorno = $this->_argsAsociativos;
                break;
        }
        
        return $retorno;
    }
    
    public function getArgGet($nombre, $default = false)
    {
        return $this->getUrl($nombre, 'GET', $default);
    }
    
    /**
     * Retorna el valor para el parametro solicitado por nombre.
     * @param   string  $nombre  Nombre del Parametro deseado.
     * @param   mixed   $default Valor a devolver en caso de que no exista el parametro.
     * @return  mixed            Valor del Parametro deseado.
     */
    private function getUrl($nombre, $tipo, $default = false)
    {
        $valor  = $default;
        if (array_key_exists($nombre, $this->_argsAsociativos[$tipo])) {
            $valor = $this->_argsAsociativos[$tipo][$nombre];
        }
        
        return $valor;
    }
    
    public function isAjax()
    {
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        } else {
            return false;
        }
    }
    
    
    
}

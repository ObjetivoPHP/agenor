<?php
namespace agenor\mvc;

/**
 * Clase FrontController.
 * Se encarga de Enrutar la Aplicacion.
 *
 * @package     MVC creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.1 (13/09/2012 - 16/09/2012)
 */
class FrontController
{
    /**
     * Contiene la Peticion Original del sistema.
     * @var \agenor\mvc\Request
     */
    private $_peticion;
   
    /**
     * Contiene la Peticion antes de ejecutar la accion correspondiente.
     * @var string  acccion|controlador|modulo 
     */
    private $_peticionIn;
    
    /**
     * Registra las Acciones PRE y POST controlador.
     * Estas clases deberan extender de plugins y recibiran un objeto Request.
     * Solo se ejecutara el metodo execute.
     * array del tipo array('clase', index)
     * @var type 
     */
    private $_actions = array();
    
    
    /**
     * Constructor del Controlador Frontal, se encarga de enrutar al controlador
     * definitivo y accion correspondiente.
     * 
     * @param \agenor\mvc\Request $peticion Peticion pasada por URL por el usuario.
     * @return void
     */
    public function __construct()
    {
        //$this->_peticion = $peticion;
        //$this->_router();
    }
    
    public function run(Request $peticion)
    {
        $this->_peticion = $peticion;
        $this->_router();
    }
    
    /**
     * Retorna un Objeto FrontController.
     * Puede ser utilizado para registrar Eventos que se ejecuten antes de los 
     * controladores y despues de estos.
     * @return \agenor\mvc\FrontController
     */
    public function getInstance()
    {
        return $this;
    }
    
    /**
     * Enruta la peticion hacie el Modulo, controlador y accion,
     * Pasada por Parametro.
     * 
     * @return void
     */
    private function _router()
    {
        // Ejecuto los Plugins de Inicio
        isset($this->_actions['PRE'])?  $this->puginDispatch($this->_actions['PRE']) : null;
            
        // Levanto el Controlador que se quiere ejecutar.
        $controller     = DIR_MODULO . $this->_peticion->getModulo() . DS . 'controllers' 
                        . DS .  $this->_peticion->getControlador();
        $controllerClass= MODULO_NAME_SPACE . $this->_peticion->getModulo() . '\controllers\\' 
                        . $this->_peticion->getControlador();
        try {
            if (!file_exists( DIR_BASE . $controller . '.php')) {
                $controllerClass= MODULO_NAME_SPACE . 'index\controllers\IndexController';
                $this->_peticion->setAction('AccionQueNoDeberiaExistir'); // Sacar en un Futuro
                throw new \Exception('Error de Controlador');
            }
        } catch (\Exception $exc) {
            \agenor\mvc\CaptureException::registrarLog($exc);
        }
        $controlador    = new $controllerClass();
        // Le paso al Controlador la Vista y El FrontController
        $controlador->setView()->setController($this);
        $controlador->getView()->setController($controlador);
        //$controlador->setController($this);
        // Si solo seteo el modelo en el bucle do while, no tengo disponible el modelo en el init.
        $controlador->setModel($this->_peticion->getModulo(),
                               $this->_peticion->getControlador(false));
        $this->exeMethod($controlador,'init');
        
        // Ejecuto la Accion Principal y Algun cambio que pudiera Existir.
        do {
            // ###DEBUG###
            Debug::variable($this->_peticion, 'Peticion');
            $this->_peticionIn  = $this->_peticion->getAccion() . '|'
                                . $this->_peticion->getControlador() . '|'
                                . $this->_peticion->getModulo();
            
            $controlador->setModel($this->_peticion->getModulo(),
                                   $this->_peticion->getControlador(false));
                           
            $this->exeMethod($controlador, $this->_peticion->getAccion(), true);
                    
            $peticionOut    = $this->_peticion->getAccion() . '|'
                            . $this->_peticion->getControlador() . '|'
                            . $this->_peticion->getModulo();
            //Debug::variable($this->_peticionIn, 'Peticion In');
            //Debug::variable($peticionOut, 'Peticion Out');
        } while ($this->_peticionIn != $peticionOut);
        
        if ($controlador->getView()->getRender() == false ) {
            $controlador->getView()->render(array());
        }
                
        $this->exeMethod($controlador,'post');
        // Ejecuto los Plugins de Inicio
        isset($this->_actions['POST'])?  $this->puginDispatch($this->_actions['POST']) : null;
    }

    /**
     * Se encarga de Ejecutar un Metodo, del Controllador solicitado.
     * 
     * @param   object  $objeto         Objeto (Controlador) Solicitado.
     * @param   string  $metodo         Nombre del Metodo Solicitado
     * @param   boolean $obligatorio    Establece si se lanza una excepcion en caso de 
     *                                  ser obligatoria la Ejecucion del Metodo.
     * @throws \Exception               Metodo Inexistente y Obligatorio.
     * @return  void
     */
    public function exeMethod($objeto, $metodo, $obligatorio = false)
    {
        if (method_exists($objeto, $metodo)) {
            try {
                $objeto->$metodo();
            } catch (\Exception $exc) {
                CaptureException::registrarLog($exc);
            }
        } elseif ($obligatorio) {
            try {
                throw new \Exception("El Metodo($metodo) no existe.");
            } catch (\Exception $exc) {
                CaptureException::registrarLog($exc);
            }
        }
    }
    
    /**
     * Cambia la accion, controlador y modulo que se esta ejecutando dentro de 
     * la misma peticion HTTP.
     * 
     * @param   string  $accion         Accion que se desea ejecutar.
     * @param   string  $controlador    Controlador que se desea ejecutar.
     * @return  void
     */
    public function changeAction($accion, $controlador = '')
    {
        if ($controlador != '') {
            $this->_peticion->setControlador($controlador);
            $this->_peticion->setAction($accion);
            $this->_peticionIn = '';
            $this->_router();
        } else {
            $this->_peticion->setAction($accion);
            \agenor\mvc\Registry::get('Request')->setAction($accion);
        }
    }
    
    /**
     * Realiza una nueva peticion con nuevos datos de modulo, controlador y accion.
     * 
     * @param string    $accion         Accion a Ejecutar
     * @param string    $controlador    Controlador a Ejecutar
     * @param string    $modulo         Modulo a Ejecutar
     * @return  void    Realiza una nueva Peticion HTTP o HTTPS
     */
    public function newHttp($accion, $controlador = '', $modulo = '', $peticionNew = '')
    {
        $https      = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']    == 'on')? 'https://' : 'http://';
        
        $url        = $modulo . '/' . $controlador . '/' . $accion; 
        $peticion   =  Registry::get('Request');
        
        if ($peticionNew == '') {
            if (count($peticion->getArgs()) > 0 ) {
                $url    = $url . '/' . implode('/', $peticion->getArgs());
            }
        } else {
           $url    = $url . '/' . $peticionNew;    
        }
        
        $url        = $https . URL_BASE . '/' . $url;
        
       header('Location: ' . $url);   
    }
    
    /**
     * Configura Plugins que se ejecutaran antes del inicio de los metodos init.
     * @param   \agenor\mvc\Plugin $plugin  Nombre de Clase del Plugin
     * @param   string  $moment             Si se ejecuta al antes de INIT = PRE
     *                                      Si se ejecuta luego de POST = POST  
     * @param   integer $index              orden de ejecucion.
     */
    public function setPlugins($plugin, $moment = 'PRE', $index = 0)
    {
        $momento    = ($moment == 'PRE')? 'PRE' : 'POST';
        
        while (!isset($this->_actions[$momento][$index])) {
            $this->_actions[$momento][$index]   = $plugin;
        }
    }
    
    /**
     * Ejecuta los Plugines.
     * @param array $plugins    Arreglo conteniendo los Plugins a ejecutar
     * @throws \Exception       Si no se encuentra el Plugin
     */
    public function puginDispatch(array $plugins)
    {
        foreach ($plugins as $plugin) {
            if ('\plugins\\' . $plugin instanceof \agenor\mvc\Plugin) {
                $objeto     = '\plugins\\' . $plugin;
                $metodo     = 'execute';
                $objeto::$metodo();
                //$ejecutar();
            } else {
                 throw new \Exception('El Plugin ' . $plugin . ' no pudo cargarse');
            }
        } 
    }
}


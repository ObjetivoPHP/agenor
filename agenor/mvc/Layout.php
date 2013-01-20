<?php
namespace agenor\mvc;

/**
 * Clase Layout.
 * Se encarga de la Administracion de la plantilla principal del sitio.
 *
 * @package     mvc creado en el projecto Agenor
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.3.0 (18/09/2012 - 15/10/2012)
 * @tutorial    Las variables que levantan plantillas todas hacen referencia hacia el
 *              directorio principal de la plantilla.
 *              en este caso es por defecto. /layout/default/phtml.
 *              Widget Dinamicos se Ejecutan en Tiempo de Ejecucion.
 */
abstract class Layout
{
    /**
     * Contiene el directorio de las plantillas.
     * Por defecto default/
     * @var string
     */
    protected $_dir = 'default';
    
    /**
     * Contiene el Nombre de la plantilla principal que se debera cargar.
     * Por defecto levanta del directorio /layout/default/phtml el archivo layout.phtml
     * @var string
     */
    protected $_layout = 'layout.phtml';
    
    /**
     * Contiene la codificacion de la pagina web.
     * @var string
     */
    protected $_charset = 'utf-8';

    /**
     * Contiene el nombre que se presenta en la barra superior del navegador.
     * @var string
     */
    protected $_title = 'Sistema de Informacion COT ';

    /**
     * Contiene la plantilla que sera usada como cabezal de la pagina.
     * @var string 
     */
    protected $_header = '';
    
    /**
     * Contiene la plantilla que sera usada como menu de navegacion.
     * @var string
     */
    protected $_nav = '';
    
    /**
     * Contiene las Plantillas que se usaran como widgets del sitio.
     * Formato.
     * array('file' => 'widget.php', 'modulo', 'controlador', 'accion');
     * @var array
     */
    protected $_widget = array();

    /**
     * Contiene la plantilla que sera el pie de la pagina.
     * @var string
     */
    protected $_footer = '';
    
    /**
     * Es una contenedor de datos, tipo standard class
     * 
     * @var \stdClass
     */
    private  $_datos;

    /**
     * Contiene la Url base del sitio, es decir www.agenor.com.uy por ej.
     * @var string 
     */
    private $_urlBase;
    
    /**
     * Contiene si se renderiza o no la plantilla.
     * @var Boolean
     */
    protected $_render;
    
    /**
     * Los Widgets Dinamicos Son Widgets a los cuales se le puede dar un nombre,
     * para luego tener un mayor control dado que se podran apagar y encender con mayor dinamismo.
     * <code>
     * array = array('NombreWidget' => array('modulo'       => 'nombreDeModulo',
     *                                       'controlador'  => 'nombreDeControlador,
     *                                       'accion'       => 'nombreDeLaAcccion'));
     * </code>
     * @var array 
     */
    private $_widgetDinamicos = array();
    
    
    protected $_idioma;
    
    /**
     * Constuctor de layout.
     * 
     * @param   string  $dirLayout  Directorio dentro de layout donde estaran las plantillas principales.
     * @param   string  $layout     Nombre de la plantilla principal.
     * @return  void
     */
    public function __construct($dirLayout = '', $layout = '')
    {
        
        $https          = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']    == 'on')? 'https://' : 'http://';
        $this->_urlBase = $https . URL_BASE;
        
        $this->_datos = new \stdClass();
        $layoutIni = Registry::get('application')->layout;
        // Cargo el Directorio de las layout por defecto es default.
        if (trim($dirLayout) != '' || $layoutIni->dir!= '' ) {
            $this->_dir = (trim($dirLayout) != '') ? trim($dirLayout) : $layoutIni->dir;
        }
        $this->_dir = $this->_dir . DS;
        
        // Cargo el nombre de archivo de la plantilla principal.
        if (trim($layout) != '' || $layoutIni->main!= '' ) {
            $this->_main = (trim($layout) != '') ? trim($layout) : $layoutIni->main;
        }
    }
    
    /**
     * Configura el Directorio donde se encuntra el Layout.
     * @param   string  $dir    Directorio del layout
     */
    public function setLayoutDir($dir)
    {
        if (!file_exists($dir)) {
            throw new \Exception("El directorio de plantillas no existe.");
        }
        $this->_dir     = $dir;
        //$this->_dir     = \agenor\mvc\Registry::get('application')->layout->dir;
        //$this->_layout  = \agenor\mvc\Registry::get('application')->layout->main;
    }
    
    /**
     * Configura la plantilla a utilizar, si difiere de la Configuracion.
     * @param   string  $nombre Nombre de la Plantilla.
     * @throws \Exception   La plantilla No existe
     */
    public function setLayout($nombre)
    {
        if (!file_exists(DIR_BASE . 'layout' . DS . $this->_dir . 'phtml' . DS . $nombre)) {
            throw new \Exception("No existe la Plantilla.");
        }
        
        $this->_layout  = $nombre;  
    }
    
    /**
     * Configura Variables que ya se encuentran en la plantilla, en la plantilla
     * deben ser llamada como $this->nombreDeVariable.
     * 
     * @param   string $nombre  Nombre de la Variable
     * @param   string $valor   Valor que se le desea Cargar.
     * @return  void
     */
    public function __set($nombre, $valor)
    {
        if ( strpos($nombre,'_') === 0) {
                
            $this->$nombre = $valor; 
        } else {
            $this->_datos->$nombre = $valor;
        }  
    }
    
    /**
     * Retorna el valor de una variable de plantilla.
     * 
     * @param   string  $nombre Nombre de la variable que se desea su valor.
     * @return  mixed
     */
    public function __get($nombre)
    {
        try {
            if ( strpos($nombre,'_') === 0) {
                return $this->$nombre;
            } else {
                return $this->_datos->$nombre;
            }
        } catch (\Exception $e) {
            if (Registry::get('environment') == 'debug') {
               echo "Variable $nombre no Existe";
            }
            return '';
        }
    }
    
    /**
     * Levanta la Plantilla para el Header en caso de existir.
     * @param string $header    Plantilla Cabezal.
     */
    public function header($header = 'header.phtml')
    {
        $this->_loadView($header);
    }
    
    /**
     * Levanta los Widgets del sistema y modulo.
     * Los widgets del sistema deben de proveer accion, controlador y modulo.
     * Los widgets del los modulos solo necesitan proveer de accion y controlador.
     * Si se requiere apagar los Widgets del Sistema en un Modulo lo podemos hacer en 
     * el bootstrap con un metodo start.
     * Si es para un controlador en el metodo init.
     * Y si es para una accion en la propia accion.
     * haremos Registry::get('application')->layout->widget = 0; 
     * 
     * @throws \Exception   Si no se encuentra la plantilla de Widgets
     */
    public function widget()
    {
       /* $modulo = Registry::get('Request')->getModulo();
        $widget = Registry::get('application')->layout;
        
        // Carga Widget Principales si se configuro de ese modo.
        if ($widget->widget) {
            $widget->dir      = 'default';
            $this->_loadTemplateWidget(DIR_BASE . 'layout' . DS . $widget->dir . DS . 'phtml' . DS . 'widget.phtml');
        }
        $this->_loadTemplateWidget(DIR_BASE . DIR_MODULO . $modulo . DS . 'layout' . DS . 'phtml' . DS . 'widget.phtml');
        */
        // Levanto los Widgets Dinamicos
        if (!$this->_render) {
            foreach ($this->_widgetDinamicos as $widget) {
                $this->loadWidget($widget['modulo'], $widget['controlador'],$widget['accion']);   
            }            
        }
    }
    
    /** 
     * Levanta los Widget solicitados en la Plantilla de Widgets del Modulo.
     * 
     * @param   string  $controlador    Nombre del Controlador a Ejecutar.
     * @param   string  $accion         Nombre de la accion a Ejecutar.
     * @param   string  $modulo         Por defecto es el modulo actual.
     * 
     * @throws \Exception               Si no se puede levantar el Widget correspondiente.
     */
    public function loadWidget($modulo, $controlador, $accion)
    {
        // Ejecuto el Controlador primero
        
        $controller     =  DIR_MODULO . $modulo . DS . 'controllers' . DS 
                        . $controlador . 'Controller';

        $controllerClass=  MODULO_NAME_SPACE . $modulo . '\controllers\\' 
                        . $controlador . 'Controller';        
        
        // Idioma del Widget
        $rutaIdioma = DIR_BASE . DIR_MODULO . $modulo . DS . 'layout'. DS . 'phtml'  . DS . 'languages' . DS 
                    . $this->_idioma . DS
                    . strtolower($controlador) . '_' 
                    . $accion . '.php';
        if (file_exists($rutaIdioma)) {
            require_once($rutaIdioma);    
        }  
        
        // Controlo que exista el Archivo.
        if (!file_exists( DIR_BASE . $controller . '.php')) {
            throw new \Exception('No se pudo cargar el Widget');
        }
        
        $action         = $accion . 'Action';
        $obj            = new $controllerClass();
        
        if (!method_exists($obj, $action)) {
            throw new \Exception('No se pudo ejecutar el Widget.');
        }
        
        $obj->$action();
        
        // Levanto la plantilla del Widget
        $ruta = DIR_BASE . DIR_MODULO . $modulo . DS . 'layout' . DS . 'phtml' . DS  
              . strtolower($controlador) . '_' 
              . strtolower($accion) . '.phtml';
        if (!file_exists($ruta)) {
            throw new \Exception('No se Encontro la Plantilla del Widget');
        }
        
        require_once $ruta;
    }    
    
    /**
     * Carga un WidgetDinamico en el Layout
     * @param   string  $nombre         Nombre identificador
     * @param   string  $modulo         Nombre del Modulo
     * @param   string  $controlador    Nombre del Controlador
     * @param   string  $accion         Nombre de la Accion
     * @return  void
     */
    public function addWidget($nombre, $modulo, $controlador, $accion)
    {
        $this->_widgetDinamicos[$nombre]    = array('modulo'        => $modulo,
                                                    'controlador'   => $controlador,
                                                    'accion'        => $accion);
    }
    
    /**
     * Elimina un Widget Dinamico identificandolo por su nombre
     * @param   string  $nombre     Nombre del WidgetDinamico
     */
    public function removeWidgetDinamico($nombre)
    {
        if (isset($nombre, $this->_widgetDinamicos) ) {
            unset($this->_widgetDinamicos[$nombre]);
        }
    }
    
    /**
     * Coloca un Widget al Principio de todo.
     * @param string $nombre
     */
    public function firstWidget($nombre)
    {
        if (isset($nombre, $this->_widgetDinamicos)) {
            $widget = $this->_widgetDinamicos[$nombre];
            unset($this->_widgetDinamicos[$nombre]);
            array_unshift($this->_widgetDinamicos, $widget); 
        }
    }
    
    /**
     * Coloca un Widget al Final de todo.
     * @param string $nombre
     */
    public function endWidget($nombre)
    {
        if (isset($nombre, $this->_widgetDinamicos)) {
            $widget = $this->_widgetDinamicos[$nombre];
            unset($this->_widgetDinamicos[$nombre]);
            $this->addWidgetDinamico($nombre, $widget['modulo'], $widget['controlador'], $widget['accion']);
        }   
    }
    
    /**
     * Carga un Widget por su ruta
     * @param   string  $ruta   Ruta completa del Widget
     * @deprecated since version 0.2.6 Dejar de usar en Futuras versiones desaparecera
     * @throws  \Exception  Si no se encuentra la Plantilla
     */
    private function _loadTemplateWidget($ruta)
    {
        if (file_exists($ruta)) {
            require_once $ruta;
        } else {

                throw new \Exception("La plantilla de  widgets no fue encontrada.");
        }   
    }

    /**
     * Levanta la plantilla de navegacion.
     * @param string $nav Plantilla de menu a levantarse.
     */
    public function nav($nav = 'nav.phtml')
    {
        $this->_loadView($nav);
    }
    
    /**
     * Levanta la plantilla del pie de pagina.
     * @param string $footer Plantilla para el Pie de Pagina.
     */
    public function footer($footer = 'footer.phtml')
    {   
        $this->_loadView($footer);
    }
    
    /**
     * Carga el Archivo tipo plantilla(vista que se le manda.)
     * @param   string  $file   Nombre del archivo a cargar. Su extencion debe ser .phtnml.
     * @throws \Exception       Si no se encuentra el archivo.
     */
    protected function _loadView($file)
    {
        $ruta   = DIR_BASE . 'layout' . DS . $this->_dir . 'phtml' . DS . $file;
        if (file_exists($ruta)) {
            require_once $ruta;
        } else {

                throw new \Exception("La vista ($file) no fue encontrada.");
        } 
    }
    
    /**
     * Carga una Plantilla PHTML a demanda.
     * @param   string  $dir    Directorio del cual se cargara.
     * @param   string  $file   Nombre del Archivo sin la extencion, la cual debera ser
     *                          phtml
     * @return  void            Plantilla PHTML.
     */
    public function includePhtml($dir, $file)
    {
        $ruta   = $dir . DS . $file . '.phtml';
        if (file_exists($ruta)) {
            require_once $ruta;
        } else {
            throw new \Exception("La plantilla(phtml: $ruta) no fue encontrada por el Metodo includePhtml.");
        }         
    }
}
  
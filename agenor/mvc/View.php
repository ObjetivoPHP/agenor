<?php
namespace agenor\mvc;

/**
 * Clase View.
 * Se encarga de Renderizar la vista.
 *
 * @package     MVC creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.3.0 (13/09/2012 - 04/10/2012)
 */
class View extends Layout implements \ArrayAccess
{
    /**
     * Contiene el Controlador. 
     * @var FrontController 
     */
    private $_controller;
    
    /**
     * Contiene un arreglo con los archivos css que se deveran levantar.
     * @var array 
     */
    private $_css   = array();
    
    
    /**
     * Contiene un arreglo con los archivos javascript a levantar.
     * @var type 
     */
    private $_js    = array();

    /**
     * Contiene el Ultimo directorio de Plantilla Usado.
     * @var string 
     */
    private $_dirLayoutBase;
    
    /**
     * Contiene el Controlador que sera renderizado.
     * @var string
     */
    private $_renderController = '';
    
    /**
     * Contiene la Accion que sera Renderizada.
     * @var string 
     */
    private $_renderAction = '';

    /**
     * Contiene el Modulo que se esta ejecutando.
     * @var string 
     */
    private $_modulo = '';

    /**
     * Constructor de la Vista.
     * 
     * @param   string  $dirLayout  Directorio dentro de layout donde estaran las plantillas principales.
     * @param   string  $layout     Nombre de la plantilla principal.
     * @return  void
     */
    public function __construct(Request $peticion = null,  $dirLayout = '', $layout = '')
    {
        parent::__construct($dirLayout, $layout);
        $peticion = Registry::get('Request');
        
        $this->_renderController    = strtolower($peticion->getControlador(false));
        $this->_renderAction        = strtolower($peticion->getAccion(false));
        $this->_modulo              = strtolower($peticion->getModulo());
        
    
    }
    
    /**
     * Configura el Lenguaje de la Aplicacion. Si no por defecto toma el del
     * archivo de configuracion.
     * @param string    $idioma     Idioma de la aplicacion.
     */
    public function setLanguage($idioma)
    {
        $this->_idioma = $idioma;
    }
    
    public function getLanguage()
    {
        return $this->_idioma;
    }
    
    public function setController(Controller $controller)
    {
        $this->_controller = $controller;
    }

    /**
     * Renderiza la vista y la envia por pantalla.
     * 
     * @param   array   $datos  Arreglo conteniendo los datos para la vista.
     * @return  boolean         falso por si da error.
     * @throws \Exception       Si no existe vista o datos, lanza una excepcion.
     */
    public function render(Array $datos = array(), $capture = false)
    {
        //
        if ($capture) {
            ob_start();
        }
        
        
        // ruta donde se encuentra alojada la plantilla.
        $rutaPlantilla = DIR_BASE . 'layout' . DS . $this->_dir . 'phtml' . DS . $this->_layout;
        $this->_dirLayoutBase = $rutaPlantilla;
        
        // ###DEBUG###
        Debug::variable($rutaPlantilla, 'Plantilla');
        
        //Si no existe el fichero en cuestion, tiramos un error
        if (file_exists($rutaPlantilla) == false) {
            throw new \Exception('No se Encontro la Plantilla ' . $this->_layout);
            return false;
        }
 
        // Levanto idioma layout e idioma accion
        if (!$this->_idioma) {
            $this->_idioma = Registry::get('application')->layout->idioms;
        }
        $rutaConfig =  DIR_BASE . 'layout' . DS . $this->_dir . 'phtml' . DS . 'languages' . DS . $this->_idioma . '.php';
        if (file_exists($rutaConfig)) {
            require_once($rutaConfig);    
        }
        
        if(is_array($datos)) {
            //extract($datos);
            foreach ($datos as $clave => $valor) {
                $this->$clave = $valor;
            }
         } else {
             throw new \Exception('No se Pasaron datos validos ');
             return false;
         }

        require_once($rutaPlantilla);
        $this->_render = true;
        
        if ($capture) {
            $contenido  = ob_get_contents();
            ob_clean();
            return $contenido;
        }
    }
    
    /**
     * Configura si se mostrara o no la vista. 
     * 
     * @example Util para ajax estableciendo la propiedad render a false.
     * @param boolean $render   true: Se muestra la Vista.
     *                          false: No se muestra la vista.
     * @return void
     */
    public function setRenderView($render = true) 
    {
        $this->_render = ($render)? false : true;
    }
    
    /**
     * Captura la vista pero no la lanza por pantalla.
     * @param   array   $datos  Arreglo conteniendo los datos para la vista.
     * @return  string          Tipo HTML captura de vista.
     */
    public function capture(Array $datos)
    {
        ob_start();
        $this->render($datos);
        $captura    = ob_get_contents();
        ob_clean();
        
        return $captura;
    }
    
    public function helper($helper)
    {
        $rutaHelper = DIR_BASE . 'agenor' . DS . 'helpers' . DS . $helper . '.php';
        
        // ###DEBUG###
        Debug::variable($rutaHelper, 'Helper Dir');
        
        if (!file_exists($rutaHelper)) {
            throw new \Exception('No se encontro el Helper ' . $helper);
        }
        $clase = "\\agenor\\helpers\\" . $helper;
        $dirFile     = URL_BASE . '/layout/' . \agenor\mvc\Registry::get('application')->layout->dir;
        
        $helper = new $clase($dirFile);
        
        return  $helper;
    }
    
    /**
     * Dice si se renderizo o no la vista.
     * 
     * @return Boolean
     */
    public function getRender()
    {
        return $this->_render;
    }
    
    
    /**
     * Retorna el Ultimo directorio de Plantilla Base Usado.
     * @return string
     */
    public function getDirBase()
    {
        return $this->_dirLayoutBase;
    }
    
    /**
     * Retorna la plantilla del modulo, controlador y accion correspondiente.
     * @return HTML Retorna una plantilla HTML.
     * @throws \Exception Si no existe la Plantilla Envia un ERROR DE PAGINA NO ENCONTRADA
     */
    private function _content()
    {       
        $ruta   = DIR_BASE . DIR_MODULO . $this->_modulo . DS . 'layout' . DS . 'phtml' . DS  
                . $this->_renderController . '_' 
                . $this->_renderAction . '.phtml';
        
        // Levanto idioma Controlador e idioma accion
        if (!$this->_idioma) {
            $this->_idioma = Registry::get('application')->layout->idioms;
        }
        
        //$rutaIdioma =  DIR_BASE . 'layout' . DS . $this->_dir . 'phtml' . DS . 'languages' . DS . $this->_idioma . '.php';
        $rutaIdioma = DIR_BASE . DIR_MODULO . $this->_modulo . DS . 'layout'. DS . 'phtml'  . DS . 'languages' . DS 
                    . $this->_idioma . DS
                    . $this->_renderController . '_' 
                    . $this->_renderAction . '.php';

        if (file_exists($rutaIdioma)) {
            require_once($rutaIdioma);    
        }        
        if (file_exists($ruta)) {
            require_once $ruta;
        } else {
            try {
                // Si no esta la Vista del contenido lanzo una excepcion y Mando a 
                // Renderizar index/error
                throw new \Exception('No se encontro la plantilla de contenido');
            } catch (\Exception $exc) {
                $this->titulo   = 'ERROR 404 !!!';
                $this->error    = 'La pagina No existe';
                $this->error();
            }   
        } 
    }
    
    /**
     * Renderiza Solo la parte de contenido de una plantilla pudiendola capturar 
     * o retornar como HTML.
     * @param   boolean $capture    Especifica si se captura o no un contenido.
     * @return  string
     */
    public function content($capture = false)
    {
        try {
            if ($capture) {
                ob_start();
                $this->_content();
                $cache  = ob_get_contents();
                ob_end_clean();
                return $cache;
            } else {
                $this->_content();
            }
        } catch (\Exception $exc) {
           CaptureException::registrarLog($exc);
        }
        return;
    }

    /**
     * Hace un Renderizado de una Plantilla Cualquiera, no importando el modulo, controlador
     * o accion en ejecuccion y lo captura para retornarlo como string. A diferencia de los otros
     * modos puede ser mas costoso si pues incluye nuevamente en cada llamada la plantilla.
     * Utiles para bucles de renderizado de una misma plantilla.
     * 
     * @param   array       $datos              Datos que deberean ser presentados en la Plantilla.
     * @param   string      $dirFileName        Nombre completo de la plantilla a renderizar incluyendo la ruta o path.
     * @param   string      $dirFileNameIdioms  Identico al dirFileName pero para los datos de idioma.
     * @return  string                          Pagina renderizada
     * @throws \Exception
     */
    public function renderPartial(array $datos, $dirFileName, $dirFileNameIdioms ='')
    {
        if ($dirFileNameIdioms && file_exists($dirFileNameIdioms)) {
            require_once($dirFileNameIdioms);    
        }
        
        ob_start();
        if (file_exists($dirFileName)) {
            if(is_array($datos)) {
                foreach ($datos as $clave => $valor) {
                    $this->$clave = $valor;
                }
            }
            // Levanto la Plantilla
            require $dirFileName;
        } else {
            try {
                // Si no esta la Vista del contenido lanzo una excepcion y Mando a 
                // Renderizar index/error
                throw new \Exception('No se encontro la plantilla de contenido');
            } catch (\Exception $exc) {
                $this->titulo   = 'ERROR 404 !!!';
                $this->error    = 'La pagina No existe';
                $this->error();
            }   
        }
        $retorno = ob_get_contents();
        ob_end_clean();
        return $retorno;
    }
    


    private function error()
    {
        $this->_loadView('error.phtml');
    }
    
    
    /**
     * Cambia la plantilla de contenido que se mostrara.
     * 
     * @param   string $action        Accion que se ejecutara
     * @param   string $controller    Controlador que se ejecutara
     * @return  void
     */
    public function renderChange($action, $controller = '')
    {
        $this->_renderAction = $action;
        if ($controller != '') {
            $this->_renderController = $controller;
        }
    }
    
    /**
     * Configura cuales Css de Modulo o Controllador o Accion se deben levantar.
     * 
     * @param   string $urlAbsoluta Url Absoluta del estilo Css.
     * @param   string $media       screen, print, all.
     */
    public function setCSS($urlAbsoluta, $media = 'screen' )
    {
        $this->_css[]   = array('url'   => $urlAbsoluta,
                                'media' => $media);
    }
    
    /**
     * Configura cuales js de moudlo o controllador o accion se deben levantar.
     * 
     * @param string    $urlAbsoluta    Url absoluta del archivo js.
     */
    public function setJs($urlAbsoluta)
    {
        $this->_js[]    = $urlAbsoluta;
    }
    
    /**
     * Retorna el Codigo HTML para incluir hojas de estilo.
     * Se recomienda con hojas de estilo del Modulo.
     * Para el Sitio Usar Helpers en el Layout o Escritura HTML5 Normal.
     * @return string   Codigo HTML5
     */
    public function getCss()
    {
       $cssHtml5       = '';
        foreach ($this->_css as $css) {
            $cssHtml5  .=  '<link rel="stylesheet" href="' . $css['url'] . '" media="' . $css['media'] . '" >' . "\n";
        }
        return $cssHtml5;
    }
    
    /**
     * Retorna el codigo HTML para incluir hojas javascript
     * 
     * @return string   Codigo HTML5
     */
    public function getJs()
    {
        $jsHtml5        = '';
        foreach ($this->_js as $js) {
            $jsHtml5   .= '<script type="text/javascript" src="' . $js  . '"></script>' . "\n";
        }
        return $jsHtml5;
    }
    
    // Implementacion de ArrayAcces
    private $_container = array();
    
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->_container[] = $value;
        } else {
            $this->_container[$offset] = $value;
        }
    }
    
    public function offsetExists($offset)
    {
        return isset($this->_container[$offset]);
    }
    
    public function offsetUnset($offset)
    {
        unset($this->_container[$offset]);
    }
    public function offsetGet($offset)
    {
        return isset($this->_container[$offset]) ? $this->_container[$offset] : '#';
    }  
    
    
}

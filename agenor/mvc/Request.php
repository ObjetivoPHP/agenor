<?php
namespace agenor\mvc;

/**
 * Clase Request.
 * Analiza la peticion que llega por la URL y nos da informacion del
 * modulo, controlador, accion y parametros que se pasaron.
 *
 * @package     MVC creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.3.0 (10/05/2012 - 07/10/2012)
 */
class Request
{
    /**
     * Contiene el Modulo que se desea ejecutar.
     * @var string
     */
    private $_modulo;
    
    /**
     * Contiene el Controlador que se desea ejecutar.
     * @var string
     */
    private $_controlador;
    
    /**
     * Contiene la funcion que se va a ejecutar.
     * @var string
     */
    private $_accion;
    
    /**
     * Contiene los Argumentos que se pasan por URL.
     * @var array 
     */
    private $_args = array();
    
    /**
     * Contiene un Arreglo asociativo de Parametro y Valor.
     * @var array 
     */
    private $_argsAsociativo = array();
    
    /**
     * Constructor de Clase.
     * Realiza la extraccion de los datos que provienen de la URL.
     * @return void 
     */
    public function __construct()
    {
        //self::globals(); Activar en PHP Menor 5.3.0
        if (isset($_GET['urlAgenor'])) {
            $url = filter_input(INPUT_GET , 'urlAgenor', FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            $url = array_filter($url);
        }
        
        $this->_modulo      = strtolower(array_shift($url));
        $this->_controlador = array_shift($url);
        $this->_accion      = array_shift($url);
        $this->_args        = $url;
        
        $this->setModulo($this->_modulo);
        $this->setControlador($this->_controlador);
        $this->setAction($this->_accion);

        if (!$this->_args) {
            $this->_args = array();
        } 
        $this->getArgsUrl($this->_args);
    }
    
    /**
     * Genera un arreglo de parametros asociativos del formato,
     * clave => valor, para los parametros de URL, GET, POST y los sanitiza.
     * Tambien realiza un filtro generico de sanitizacion.
     * 
     * @param array $args   Arreglo de Parametros
     */
    public function getArgsUrl(Array $args)
    {
        $params = array();
        $cant   = count($args);
        
        for($i=0;$i < $cant;$i++) {
            if (($i+1) < $cant) {
                $params[$args[$i]] = $args[++$i];
            } else { 
                $params[$args[$i]] = '';
            }
        }
        // Parametros pasados por URL luego del modulo/controlador/accion
        $this->_argsAsociativo['URL'] = $params;
        
        // Cargo Los parametros que se pasaron por $_GET
        $params = array();
        $filter = array(FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH);
        foreach ($_GET as $clave => $valor) {
            $clave  = filter_var($clave,FILTER_SANITIZE_STRING, $filter);
            $valor  = filter_var($valor,FILTER_SANITIZE_STRING, $filter);
            $params[$clave] = $valor;
        }
        unset($params['urlAgenor']);
        $this->_argsAsociativo['GET']   = $params;
        
        // Cargo los Parametros que se pasaron por $_POST
        $params = array();
        foreach ($_POST as $clave => $valor) {
            $clave  = filter_var($clave,FILTER_SANITIZE_STRING, $filter);
            $valor  = filter_var($valor,FILTER_SANITIZE_STRING, $filter);
            $params[$clave] = $valor;
        }
        $this->_argsAsociativo['POST']   = $params;
    }
    
    /**
     * Retorna el Arreglo Asociativo entero.
     * @return  Array de Argumentos con clave => valor.
     */
    public function getArgsGet()
    {
        return $this->_argsAsociativo;
    }
    
    /**
     * Retorna el Modulo que se desea Ejecutar.
     * @return string 
     */
    public function getModulo()
    {
        return $this->_modulo;
    }
    
    /**
     * Retorna el Controlador que se desea Ejecutar.
     * 
     * @param   bollean     $completo   Indica si se quiere el nombre completo del Controlador o,
     *                                  Solo lo que se paso por URL.
     * @return  string      Controlador incluido en la peticion del usuario.
     */
    public  function getControlador($completo = true)
    {
        return ($completo)? $this->_controlador . 'Controller' : $this->_controlador ;
    }
    
    /**
     * Retorna la accion a ser ejecutada.
     * 
     * @param   bollean     $completo   Indica si se quiere el nombre completo de la accion o,
     *                                  Solo lo que se paso por URL.
     * @return string        Accion incluida en la peticion del usuario.
     */
    public  function getAccion($completo = true)
    {
        return ($completo)? $this->_accion . 'Action' : $this->_accion ;
    }
    
    /**
     * Retorna los Argumentos pasados por URL.
     * @return array 
     */
    public  function getArgs()
    {
        return $this->_args;
    }
    
    /**
     * Cambia la accion que se ejecutara en Tiempo de Ejecucion.
     * 
     * @param   string  $accion     Accion que se desea Ejecutar.
     * @return  string  Nueva Accion.
     */
    public  function setAction($accion)
    {
        if (!$accion) {
            $this->_accion  = 'index';
        } else {
            $this->_accion  = $accion;
        }
         
        return $this->_accion;
    }
    
    /**
     * Cambia el controlador que se ejecutara en Tiempo de Ejecucion.
     * 
     * @param   string  $controlador    Controlador que se desea ejecutar.
     * @return  string  Nuevo Controlador.
     */
    public  function setControlador($controlador)
    {
        if (!$controlador) {
            $this->_controlador = DEFAULT_CONTROLLER;
        } else {
            $this->_controlador = $controlador;
        }

        return $this->_controlador;
    }
    
    /**
     * Cambia el Modulo que se ejecutara en tiempo de ejecucion.
     * 
     * @param   string  $modulo     Modulo que se desea ejecutar.
     * @return  string  Nuevo modulo.
     */
    public  function setModulo($modulo)
    {
        if (!$modulo) {
            $this->_modulo  = DEFAULT_MODULE;
        } else {
            $this->_modulo  = $modulo;
        }
        
        return $this->_modulo;
    }

    /**
     * Extraido de KohanaFrameWork
     * http://kohanaframework.org/3.1/guide/api/Kohana_Core
     */
    public static function globals()
    {
        if (isset($_REQUEST['GLOBALS']) OR isset($_FILES['GLOBALS'])) {
            // Previene ataques Maliciosos por sobrecarga de GLOBALS
            throw new \Exception('Error en GLOBALS');
        }
 
         // Elimina las Variables Globales Estandard de la lista.
        $global_variables = array_diff(array_keys($GLOBALS), array(
        '_COOKIE',
        '_ENV',
        '_GET',
        '_FILES',
        '_POST',
        '_REQUEST',
        '_SERVER',
        '_SESSION',
        'GLOBALS'));
 
        foreach ($global_variables as $name) {
            // Desactiva Las Variables Globales.
            unset($GLOBALS[$name]);
        }
    }
    
/**
     * Removes all XSS attacks that came in the input.
     *
     * Function taken from:
     *
     * http://quickwired.com/smallprojects/php_xss_filter_function.php
     *
     * @param mixed $val The Value to filter
     * @return mixed
     */
    public static function filterXSS($val)
    {
        // Eliminamos caracteres no Imprimibles. CR(0a), LF(0b), TAB(9) es permitido
        // this prevents some character re-spacing such as <java\0script>
        // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
        $val = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $val);
 
        // straight replacements, the user should never need these since they're normal characters
        // this prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&#X3A&#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>
        $search = 'abcdefghijklmnopqrstuvwxyz';
        $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $search .= '1234567890!@#$%^&*()';
        $search .= '~`";:?+/={}[]-_|\'\\';
        for ($i = 0; $i < strlen($search); $i++) {
            // ;? matches the ;, which is optional
            // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
 
            // &#x0040 @ search for the hex values
            $val = preg_replace('/(&#[x|X]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
            // &#00064 @ 0{0,7} matches '0' zero to seven times
            $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
        }
 
        // now the only remaining whitespace attacks are \t, \n, and \r
        $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
        $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
        $ra = array_merge($ra1, $ra2);
 
        $found = true; // keep replacing as long as the previous round replaced something
        while ($found == true) {
            $val_before = $val;
            for ($i = 0; $i < sizeof($ra); $i++) {
                $pattern = '/';
                for ($j = 0; $j < strlen($ra[$i]); $j++) {
                    if ($j > 0) {
                        $pattern .= '(';
                        $pattern .= '(&#[x|X]0{0,8}([9][a][b]);?)?';
                        $pattern .= '|(&#0{0,8}([9][10][13]);?)?';
                        $pattern .= ')?';
                }
                $pattern .= $ra[$i][$j];
             }
             $pattern .= '/i';
             $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
             $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
             if ($val_before == $val) {
                // no replacements were made, so exit the loop
                $found = false;
             }
          }
        }
 
        return $val;
    }
    
    
}

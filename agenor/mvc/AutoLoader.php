<?php
/**
 * Clase AutoLoader.
 * Carga las Clases requeridas automaticamente.
 *
 * @package     MVC creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.2.0 (09/05/2012 - 01/10/2012) ***
 */
class AutoLoader
{
    /**
     * Establece si el levantado de clases es automatico o no.
     * @var boolean
     */
    private static $_estado = true;
    
    private static $_count = 0;


    /**
     * Carga una Clase o un Espacio de Nombres.
     * @param string $clase     Nombre de la Clase o Espacio de Nombre.
     * @exception               Lanza una Excepcion cuando no se puede cargar
     *                          el archivo requerido.
     */
    public static function loadClass($clase)
    {
        self::$_count++;
        if (self::$_estado) {
             $classname = str_replace('\\', DS,  ltrim($clase));        
            $filename = $classname . '.php';
            //$filename = str_replace('_', DS, $classname) . '.php';
           
            // ###DEBUG###
            if (getenv('DEBUG_URL') == 'true') {
                echo self::$_count . ' #FILE : ' . $clase . ' | ' . DIR_BASE . $filename . '#<br/>';
            }
            
           // echo 'NAMESPACE :' . __NAMESPACE__ . '<br />';
           
            
         
            if (is_readable(DIR_BASE . $filename) == true) {
                require_once DIR_BASE . $filename;
            } else {
                try {
                    throw new \Exception('No se pudo Cargar el Archivo ' . $filename);
                } catch (\Exception $exc) {
                    \agenor\mvc\CaptureException::registrarLog($exc);
                }
            }
        }
    }
    
    
    
    
    /**
     * Activa el modo automatico de Autoloader.
     * No se requerira realizar include o require para las clases que se 
     * instancien.
     * @return void
     */
    public static function on()
    {
        self::$_estado = true;
    }
    
    /**
     * Desactiva el modo automatico del Autoloader y se deberan incluir
     * las clases que se instancien.
     * @return void
     */
    public static function off()
    {
        self::$_estado = false;
    }
    
    /**
     * Incluye un directorio de sistema dentro de la ejecucion.
     * 
     * @param   string      $dir    Directorio que se desea incluir
     * @throws \Exception   Error si no existe el Directorio.
     */
    public static function setIncludePath($dir)
    {
        try {
            set_include_path(get_include_path() . PATH_SEPARATOR . $dir);
        } catch (\Exception $exc) {
            throw new \Exception($exc->getMessage());
        }
    }
    
    /**
     * Retorna los directorios de sistema.
     * 
     * @return string
     */
    public static function getIncludePath()
    {
       return get_include_path();
    }
    
    public static function unRegister($metodo = 'loadClass')
    {
        //spl_autoload_register(array('AutoLoader', 'loadClass'));  
        spl_autoload_unregister(array('AutoLoader', $metodo));
    }
    
    public static function register($metodo = 'loadClass')
    {
        spl_autoload_register(array('AutoLoader', $metodo));
    }
    
    
    /**
     * Configura el include path al original de php.
     */
    public static function restoreIncludePath()
    {
       restore_include_path();
    }
    
    /**
     * Compatible con namespaces y Formato antiguo Zend
     * @param type $className
     */
    public static function autoload($className)
    {
        self::$_count++;
        if (self::$_estado) {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strripos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }   
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
 
       // ###DEBUG###
       if (getenv('DEBUG_URL') == 'true') {
            echo self::$_count . ' #FILE : ' . $className . ' | ' . DIR_BASE . $fileName . '#<br/>';
       }
        
        
        if (!is_readable(DIR_BASE . $fileName)) {
            throw new \Exception(sprintf("La clase no existe", $className , DIR_BASE . $fileName   ));
        }
        
        require DIR_BASE . $fileName;
    }
    }
    
}

<?php
namespace agenor;
use agenor\parser;
use modulos;

/**
 * Clase AgenorApplication.
 * Es la encargada de iniciar el sistema.
 *
 * @package     Agenor creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (13/05/2012 - 23/05/2012)
 */
class AgenorApplication
{   
    /**
     * Contiene el boostrap principal.
     * @var BoostrapApplication
     */
    private $_bootstrap;
    
    /**
     * Carga el Entorno en que se ejecuta la Aplicacion y las configuraciones
     * generales del sistema.
     * @param   string  $entorno    Entorno en el que se ejecutara el Sistema.
     *                              debug o web.
     *                              debug:  se utiliza para desarrollo.
     *                              web:    se utiliza para produccion.
     * @param   string  $config     Archivo Inicial de Configuracion, por defecto
     *                              se levanta application.ini, se debe encontrar en
     *                              el directorio RAIZ\configs y debe existir 
     *                              aunque este en blanco.
     * @return  AgenorApplication
     */
    public function __construct($entorno, Array $config = array())
    {
        // Registro el Entorno
        \agenor\mvc\Registry::set('environment', $entorno);
        \agenor\mvc\Registry::set('Request', new \agenor\mvc\Request());
        // Veo que sea una Version Valida de PHP.
        $this->versionPHP();
       
        //Levanto las Configuraciones Iniciales del sistema.
        if (is_array($config)) {
            $rutaConfig = DIR_BASE . 'configs' . DS;
            foreach ($config as $file) {
               $ini = parser\ParserFileIni::parse($rutaConfig . $file . '.ini', true, mvc\Registry::get('environment'));
               // Cargo el Objeto de Configuracion en el Registry del sistema.
                 mvc\Registry::set($file,  parser\arrayMultidimensionalToObject::convert($ini));
            }
        }
        
        // Levanto el bootstrap de la aplicacion
        $bootstrap  = new modulos\BootstrapApplication();
        $bootstrap->setConfig($config);
        $bootstrap->startFrontController();
        
        // Cargo los Metodos del BoostrapApplication
        $metodos = get_class_methods($bootstrap);
        // Ejecuto los metodos que comienzan con _startxxxxx es insenciblea mayusculas
        // y minusculas.
        foreach ($metodos as $metodo ) {
            if(stripos($metodo,'_start') === 0 ) {
                $nomRecurso = substr($metodo,6);
                mvc\Registry::set($nomRecurso, $bootstrap->$metodo());
            }        
        }

        $this->_bootstrap = $bootstrap;
        mvc\Registry::set('bootstrap', $bootstrap);
        
        // ###DEBUG###
        mvc\Debug::variable($this->_bootstrap, 'BootStrap');
        
        return $this;
    }
    
    /**
     * Retorna el bootstrap principal de la aplicacion.
     * @return  BootstrapApplication
     */
    public function bootstrap()
    {
        return $this->_bootstrap ;
    }
    
    /*
     * Verifica que la Version de PHP se ajuste a lo necesitado por agenor.
     * @throws  en caso de no se compatible con la version de php, lanza una
     *          excepcion de lo contrario continua.
     */
    public function versionPHP()
    {
        if (version_compare(phpversion(), '5.3.3', '<') == true) {
            throw new \Exception('Compatible con PHP 5.3.3 o Superior,</br>' . 'Version del Servidor ' . phpversion());
        }
    }
}

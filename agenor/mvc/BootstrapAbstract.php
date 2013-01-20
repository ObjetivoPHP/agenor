<?php
namespace agenor\mvc;
use agenor\parser;


/**
 * Clase BootstrapAbstract.
 * 
 *
 * @package     MVC creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.1 (12/05/2012 - 16/09/2012)
 */
abstract class BootstrapAbstract
{
    /**
     * Contiene los Archivos de Configuracion iniciales del sistema.
     * @var Array
     */
    private static $_config;

    /**
     * Contiene un Objeto del FrontController que sera creado al Finalizar.
     * @var \agenor\mvc\FrontController
     */
    private static $_frontController;
    
    /**
     * Se encarga de Enrutar la Peticion hacia el Boostrap del Modulo
     * Correspondiente, para levantar las configuraciones.
     * @return  FrontController
     */
    public static final function run()
    {
        // Recupero La peticion Registrada.
        $peticion = Registry::get('Request');
        // Registro El Objeto Request para Tenerlo Disponible en el Sitio.
        //Registry::set('Request', $peticion);
        
        // Intento Cargar el Archivo Bootstrap del Modulo.
        $archivoBootstrapModulo = DIR_BASE . DIR_MODULO . $peticion->getModulo() . DS . 'Bootstrap.php';

        if (!is_readable($archivoBootstrapModulo)) {
            try {
                $peticion->setModulo('index');
                $peticion->setControlador('Index');
                $peticion->setAction('AccionQueNoDeberiaExistir');
                throw new \Exception('No Existe el BootStrap del Modulo o El modulo mismo.');
            } catch (\Exception $exc) {
                \agenor\mvc\CaptureException::registrarLog($exc);
            }
        }
       
        // Levanto Archivos del Modulo que sera llamado.
        $dirExploracion =  DIR_BASE . DIR_MODULO . $peticion->getModulo() . DS . 'configs';
        $directorio     = new \agenor\utils\files\Directorio($dirExploracion);
        $directorio     ->addTiposArchivosListar('ini');
        $files          = $directorio->getNombresArchivos();
        
        // ###DEBUG###
        Debug::variable($files, 'Archivos');
        
        if (is_array($files)) {
            foreach ($files as $file) {
                $file = substr($file, 0, strrpos($file,'.'));
                $ini = parser\ParserFileIni::parse($dirExploracion . DS . $file . '.ini', true, Registry::get('environment'));
                // Cargo el Objeto de Configuracion en el Registry del sistema.
                Registry::set($file,  parser\arrayMultidimensionalToObject::convert($ini));
            }
        }
        
        // Ejecuto los metodos start del bootstrap del moodulo
        // Si todo va bien se pasa la Posta al FrontController
        $bootstrap = MODULO_NAME_SPACE . $peticion->getModulo() . '\Bootstrap';

        $bootModulo = new $bootstrap();
        $metodos = get_class_methods($bootModulo);
        // Ejecuto los metodos que comienzan con _startxxxxx es insencible a mayusculas
        // y minusculas.
        foreach ($metodos as $metodo ) {
            if(stripos($metodo,'_start') === 0 ) {
                $nomRecurso = substr($metodo,6);
                \agenor\mvc\Registry::set($nomRecurso, $bootModulo->$metodo());
            }        
        } 
        
        self::$_frontController->run($peticion);
        //new FrontController($peticion);
    }
    
    /**
     * Configuramos los archivos .ini que debemos levantar.
     * @param   Array   $config     Arreglo con los archivos .ini a levantar.
     * @return  void
     */
    public final function setConfig($config = array())
    {
        self::$_config = $config;
    }
    
    /**
     * Configura el Controllador que enruta el pedido.
     * Es para tenerlo ya disponible para configurar plugins de arranque.
     * @return FrontController
     */
    public function startFrontController()
    {
        self::$_frontController = new FrontController();
        return self::$_frontController;
    }
    
    /**
     * Retorna el Enrutador en uso.
     * @return FrontController
     */
    public function getFrontController()
    {
        return self::$_frontController;
    }
}
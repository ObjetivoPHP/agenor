<?php
/**
 * Espacio de Nombre MVC, Van todas las clases que tienen vinculo con la 
 * puesta en funcionamiento de este Patron. 
 */
namespace agenor\mvc;

/**
 * Clase Registry.
 * Se encarga de almacenar y proporcionar los distintos objetos que se crean en
 * el framework y que son necesarios para su funcionamiento.
 * Ej.  - Conexiones a Base de Datos
 *      - Configuraciones del Sistema
 *      - Modulo en Ejecucion, etc
 *
 * @package     MVC creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.2.1 (09/05/2012 - 19/09/2012)
 */
class Registry
{
    /**
     * Contiene informacion de la Libreria como ser,
     * nombre, version, autor y fecha.
     * @var Array 
     */
    private static $_info   = array('nombre'        => 'Agenor',
                                    'version'       => '0.1.0',
                                    'autor'         => 'Marcelo Castro',
                                    'fecha'         => '09/05/2012',
                                    'actualizado'   => '19/09/2012');

    /**
     * Contiene el par nombre valor para cada variable de entorno.
     * @var		array
     */
    private static 	$vars;
    
    /**
     * Contiene una instancia de la clase Registry.
     * @var		object
     */
    private static 	$instance;
 
    /**
     * Metodo contructor.
     * Define a vars como array.
     */
    private function __construct()
    {	
        self::$vars = array();
    }
    
    /**
     * Retorna la Propiedad Informativa que se le solicita.
     * @param   string $propiedad   Nombre de la Propiedad que se quiere conocer.
     * @return  mixed 
     */
    public static function getPropiedad($propiedad)
    {
        $valor = '';
        if (array_key_exists($propiedad, self::$_info)) {
            $valor = self::$_info[$propiedad];
        }
        return $valor;
    }
    
    /**
     * Nos retorna si un Recurso ya fue seteado o no.
     * @param   string  $recurso    Nombre de Recurso.
     * @return  boolean
     */
    public static function isResource($recurso)
    {
        $existe = false;
        if (array_key_exists($recurso, self::$vars)) {
           $existe = true;
        }
        return $existe;
    }
    
    /**
     * Metodo set.
     * Con este metodo vamos guardando las variables.
     * @param 	string	$nombre	nombre de la variable o Recurso(Objeto).
     * @param	mixed	$valor	valor de la variables.
     * @return	void
     */
    public static function set($nombre, $valor)
    {   
                
        if(!isset(self::$vars[$nombre])) { 
            self::$vars[$nombre] = $valor;
        } else {
            throw new \Exception("Variable $nombre ya definida");
        }
    }
    
    public static function update($nombre, $valor)
    {
        if(isset(self::$vars[$nombre])) { 
            self::$vars[$nombre] = $valor;
        } else {
            throw new \Exception("Variable $nombre no definida");
        }
         
    }
    
 
    /**
     * Metodo get.
     * Nos retorna el valor que tiene la variable que le pasamos como parametro.
     * @param	string	$name	nombre de varibles.
     * @return	mixed	conteniendo el valor de la variable.
     */
    public static function get($name)
    {   
        if (array_key_exists($name, self::$vars)) {
            return self::$vars[$name];
        } else {
           throw  new \Exception("No se registro la variable $name");
        }
    }

    /**
     * Metodo singleton.
     * Retorna la instancia en uso si ya fue creada o crea una nueva.
     * @return	object
     */
    public static function singleton()
    {	
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
         return self::$instance;
    }
    
   /**
    * Impede la clonación del objeto.
    */
    public function __clone()
    {
        throw new \Exception('La clonación de la clase Registry no está permitida.');
    }
}

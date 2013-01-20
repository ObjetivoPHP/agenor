<?php
namespace agenor\utils\refleccion;
/**
 * Clase Refleccion.
 * Proporciona las propiedades sobre las clases analizadas.
 * Metodos, accesibilidad etc.
 *
 * @package     refleccion creado en el projecto ipusa
 *              modificado por agenor.
 * @copyright   2010 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.2.0 (25/08/2008 - 13/08/2010)
 * @version     0.3.0 21/12/2012 Pequeños retoques para Agenor.
 */
class Refleccion extends \Reflection
{
    /**
     * Contiene el ultimo mensaje de error que ocurrio en la clase.
     * @var string
     */
    private $_msjError;

    /**
     * Contiene el objeto de la instancia de clase.
     * @var ReflectionClass
     */
    private $_objClase;

    /**
     * Coleccion de metodos. Es un arreglo de formato [nombre] = Reflection::method
     * @var array
     */
    private $_metodos;

    /**
     * Metodo __construct.
     * Inicializa la clase.
     * @param   string      $ruta   Ruta, nombre completo de la clase a analizar.
     * @param   string      $clase  Nombre de la clase a analizar.
     * @return  boolean
     */
    public function  __construct($ruta, $clase)
    {
       /* try {
            require_once $ruta;
        } catch (Exception $error) {
            $this->_msjError = $error->getMessage();
            return false;
        }*/
        // Si existe el archivo entonces veo si existe la clase en el archivo.
        if (class_exists($clase)) {
            $this->_objClase = new \ReflectionClass($clase);
            $this->_getMetodos();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Metodo getNombre.
     * Retorna el nombre de la clase.
     * @return string
     */
    public function getNombre()
    {
        return $this->_objClase->getName();
    }

    /**
     * Metodo esInstanciable.
     * Retorna si la clase se puede instanciar o no.
     * @return boolean
     */
    public function esInstanciable()
    {
        return $this->_objClase->isInstantiable();
    }

    /**
     * Metodo esFinal.
     * Nos dice si una clase es final o no.
     * @return boolean
     */
    public function esFinal()
    {
        return $this->_objClase->isFinal();
    }

    /**
     * Metodo getMetodo.
     * Retorna un objeto con los datos del metodo pasado.
     * @param   string  $nombre Nombre del metodo que se quiere sus propiedades.
     * @return  ReflectionMethod
     */
    public function getMetodo($nombre)
    {
        if (in_array($nombre, $this->_metodos)) {
            return $this->_metodos[$nombre];
        } else {
            return false;
        }
    }

    /**
     * Metodo getMetodos.
     * Retorna un arreglo conteniendo como clave el nombre del metodo y como
     * valor un objeto ReflectionMethod.
     * @return array
     */
    public function getMetodos()
    {
        return $this->_metodos;
    }

    public function getError()
    {
        return $this->_msjError;
    }

    /**
     * Metodo _getMetodos.
     * Extrae los metodos que son de la clase en analisis, no teniendo en cuenta
     * los metodos heredados. Estos ultimos son tenidos en cuenta si son sobre-
     * escritos.
     * @return void
     */
    private function _getMetodos()
    {
        $metodos    = $this->_objClase->getMethods();
        foreach ($metodos as $metodo) {
            if ($metodo->getDeclaringClass()->name == $this->_objClase->name) {
                $this->_metodos[$metodo->getName()] = $metodo;
            }
        }
    }
    
    /**
     * Extrae los comentarios de documentacion de una clase.
     * @return string
     */
    public function getComents()
    {
        return $this->_objClase->getDocComment();
    }
    
    /**
     * Extrae los comentarios para la documentacion de un metodo.
     * @param \ReflectionMethod $metodo Nombre del metodo a extraer.
     * @return string
     */
    public function getMethodComents(\ReflectionMethod $metodo)
    {
        return $metodo->getDocComment();
    }
}
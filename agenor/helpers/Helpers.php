<?php
namespace agenor\helpers;

    
/**
 * Clase Helpers.
 * Clase tipo Factory que se encarga de instanciar el helpers adecuado.
 *
 * @package     MVC creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.2.0 (23/09/2012 - 15/10/2012)
 */
abstract class Helpers
{
    /**
     * Contiene el directorio desde donde se estan levantando los archivos.
     * 
     * @var string 
     */
    protected $_dirFile;
    
    /**
     * Identificador de Tag.
     * @var string 
     */
    protected $_id;

    /**
     * Nombre de Clase de Estilo.
     * @var string
     */
    protected $_class;

    /**
     * Constructor de Helper, nos retorna una instancia del Helper Solicitado.
     * 
     * @param   string  $dirLayout      Directorio donde se encuentra el Archivo
     * @return  \agenor\helpers\HelpersSolicitado
     */
    public final function __construct($dirFile)
    {
        $https          = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']    == 'on')? 'https://' : 'http://';
        $this->_dirFile = $https . $dirFile;
        
        return  $this;
    }
    
    /**
     * Debe existir una funcion Render que escriba html
     */
    abstract public function render();
    
    /**
     * Si nos olvidamos de Hacer render entonces al tener la fucion tostring,
     * el objeto sabra convertirse en string, lo cual no nos dara error.
     * @return  string Codigo HTML5 del Helper
     */
    public function __toString()
    {
        return $this->render();
    }
    
    /**
     * Agrega una clase de estilo al Helpers.
     * @param   string  $clase  Nombre de la clase.
     */
    public function setClass($clase)
    {
        $this->_class = ($clase)? ' class="' . $clase . '"' : '';
        return $this;
    }
    
    /**
     * Agrega un identificador al tag del Helpers
     * @param string $id    Identificador
     * @return \agenor\helpers\Helpers
     */
    public function id($id)
    {
        $this->_id = ($id)? ' id="' . $id . '"' : '';
        return $this;
    }
}


<?php
namespace agenor\mvc;

/**
 * Clase Models es la Clase Base de todos los Modelos.
 * Prepara el sistema para Utilizar el ORM que se instale.
 * Ej. La clase ModelsDoctrine Heredara de esta y debera poner en su constructor 
 * parent::__construct().
 *
 * @package     MVC creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (01/10/2012 - 01/10/2012)
 */
abstract class Models
{
    /**
     * Contiene un Objeto Vista para pasar directamente datos desde el modelo
     * a la vista.
     * 
     * @var View 
     */
    protected $_view;

    /**
     * Contiene el Administrador Principal del ORM que se Utilice en el caso
     * de Doctrine es el entityManager.
     * Nota en el Constructor de la clase que hereda se debera dar el Valor
     * correspondiente en caso de que exista.
     * $this->_ormManager   = EntityManager::create($this->_dbParams, $config);
     * 
     * @var Object(Ej. EntityManager en Doctrine) 
     */
    protected $_ormManager;
    
    /**
     * Contiene los Parametros de Conexion a la Base de Datos.
     * @var array 
     */
    protected $_dbParams = array();

    /**
     * Contiene el Modulo en Ejecucion.
     * @var string 
     */
    protected $_modulo;

    /**
     * Si no se quiere usar Doctrine se extiende en usrlib o si no lleva un 
     * modelo Persistencia, se realiza un controlador vacio sin la llamada de
     * parent.
     * @param  string Modulo en Uso.
     * @return void
     */
    public function __construct()
    {
        // Levanto los Datos de la Base y configuro la misma.
        $db                 = Registry::get('application')->database;
        $this->_dbParams    = array('driver'    => $db->driver,
                                    'host'      => $db->host,
                                    'user'      => $db->user,
                                    'password'  => $db->pass,
                                    'port'      => $db->port,
                                    'socket'    => $db->socket,
                                    'dbname'    => $db->name);
        
        // Levanto el Modulo en Ejecucion
        $this->_modulo  = Registry::get('Request')->getModulo();
    }
    
    /**
     * Retorna el administrador(Management) del ORM elegido.
     * @return mixed
     */
    public function getManager()
    {
        return $this->_ormManager;
    }    
    
    /**
     * Configura la Vista que se usara.
     * De este modo el modelo se puede comunicar con la vista directamente.
     * 
     * @param \agenor\mvc\View $view
     */
    public function setView(View $view)
    {
        $this->_view = $view;
    }  
}

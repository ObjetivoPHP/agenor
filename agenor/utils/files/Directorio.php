<?php
namespace agenor\utils\files;

/**
 * Clase Directorio.
 * Se encarga de recorrer directorios y proporcionar informacion de los archivos y
 * directorios que contiene.
 *
 * @package     archivos creado en el projecto opet
 * @copyright   2010 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.3.0 (24/03/2009 - 17/11/2010)
 */
class Directorio
{
    /**
     * Especifica que tipo de listado se retornara.
     * En este caso solo se listan los directorios.
     */
    const OP_LIST_DIRECTORIOS = 1;
 
    /**
     * Especifica que tipo de listado se retornara.
     * En este caso solo se listan los archivos.
     */
    const OP_LIST_ARCHIVOS    = 2;
 
    /**
     * Especifica que tipo de listado se retornara.
     * En este caso lista directorios y archivos.
     */
    const OP_LIST_AMBOS       = 3;
 
    /**
     * Solo se accedera de este directorio hacia abajo, a sus hijos no se listan
     * los padres del directorio. Es el directorio Base.
     * @var string
     */
    private $_dirBase = './';
 
    /**
     * Marca cuantos directorios hacia abajo listara. Con cero solo listara el
     * directorio base.
     * @var integer
     */
    private $_profundidadEscaneo = 0;
 
    /**
     * Configuramos que tipo de resultados queremos. Solo archivos, solo
     * directorios o retorna ambos.
     * @var integer
     */
    private $_listarDir = 3;
 
    /**
     * Contiene los tipos de archivos que se listaran.
     * @var array
     */
    private $_tipoArchivos = array();
 
    /**
     * Guarda los resultados de la busqueda.
     * @var array
     */
    private $_resultados = array();
 
    /**
     * Metodo __construct.
     * @param   string $dirExploracion  Directorio base de la exploracion.
     *                                  No se puede utilizar ../, en caso de necesitar
     *                                  y hacia atras utilizar direcciones absolutas.
     * @return  void
     */
    public function  __construct($dirExploracion)
    {
        // ###DEBUG###
        \agenor\mvc\Debug::variable($dirExploracion, 'Directorio Config.Modulo');
        
        if (strpos('../', $dirExploracion)) {
            trigger_error('Directorio de Exploracion no valido.', E_USER_ERROR);
        }
        $this->_dirBase = $dirExploracion;
        //date_default_timezone_set('America/Montevideo');
    }
 
    /**
     * Metodo setProfundidadEscaneo.
     * @param   integer $profundidad    Cantidad de directorios que se analizaran
     *                                  en cascada.
     * @return  void
     */
    public function setProfundidadEscaneo($profundidad = 0)
    {
        $this->_profundidadEscaneo = (int) $profundidad;
    }
 
    /**
     * Metodo setListarDirectorios.
     * @param   integer     $dir 1 directorios, 2 archivos, 3 ambos.
     * @return  void
     */
    public function setListarDirectorios($listado = 3)
    {
        switch ($listado) {
            case Directorio::OP_LIST_DIRECTORIOS:
                $this->_listarDir   = Directorio::OP_LIST_DIRECTORIOS;
                break;
            case Directorio::OP_LIST_ARCHIVOS:
                $this->_listarDir   = Directorio::OP_LIST_ARCHIVOS;
                break;
            case Directorio::OP_LIST_AMBOS:
                $this->_listarDir   = Directorio::OP_LIST_AMBOS;
                break;
            default :
                $this->_listarDir   = Directorio::OP_LIST_AMBOS;
        }
    }
 
    /**
     * Metodo addTiposArchivosListar.
     * @param   string  $extencion  Extencion de los archivos a listar.
     * @return  void
     */
    public function addTiposArchivosListar($extencion)
    {
        $this->_tipoArchivos[] = addslashes($extencion);
    }
 
    /**
     * Metodo listar.
     * Enruta las peticiones de listado al metodo correspondiente y retorna
     * los datos obtenidos.
     * @return array
     */
    public function listar()
    {
        switch ($this->_listarDir) {
            case Directorio::OP_LIST_DIRECTORIOS:
                $this->_listarDirectorios();
                break;
            case Directorio::OP_LIST_ARCHIVOS:
                $this->_listarArchivos();
                break;
            case Directorio::OP_LIST_AMBOS:
                $this->_listarTodo();
                break;
            default :
                $this->_listarTodo();
        }
        return $this->_resultados;
    }
 
 
    /**
     * Metodo ordenar.
     * Ordena los resultados de acuerdo a la clave elegida.
     * @param   string  $clave  Clave por la cual se quiere ordenar.
     * @param   boolean $asc    true para ascendente, false para descendente.
     * @return  array
     */
    public function ordenar($clave = 'name', $asc = true)
    {
        if (empty($this->_resultados)) {
            trigger_error('Primero debe usar la funcion listar o getNombresArchivos', E_USER_ERROR);
        }
 
        $tiposOrden = array('date', 'dateUpdate', 'size', 'hash', 'hash' );
        $ordenarPor = (in_array($clave, $tiposOrden))? $clave : 'name';
        $ascDesc    = ($asc)? SORT_ASC : SORT_DESC;
        $caracter   = 0;
 
        foreach ($this->_resultados as $ordenado) {
            $tmpArray[] = $ordenado[$ordenarPor];
            $caracter   = is_string($ordenado[$ordenarPor])? $caracter+1 : $caracter;
        }
       
        $numero     = ($caracter)? SORT_STRING : SORT_NUMERIC;
 
        array_multisort($tmpArray, $ascDesc, $numero, $this->_resultados);
        return $this->_resultados;
    }
 
    /**
     * Metodo _listarArchivos.
     * Genera el arreglo de resultados correspondiente solo a archivos.
     * @return void
     */
    public function _listarArchivos()
    {
        $objIterator = new \RecursiveIteratorIterator($obj_Directory = new \RecursiveDirectoryIterator($this->_dirBase),
                                                      \RecursiveIteratorIterator::SELF_FIRST);
 
        $objIterator->setMaxDepth($this->_profundidadEscaneo);
        // Recorro los Archivos.
        foreach ($objIterator as $file) {
            if (is_file($file)) {
                $ext        = substr($file, strripos($file, '.')+1);
                if (in_array($ext, $this->_tipoArchivos)) {
                    $this->_resultados[$file->__toString()] = array('date'      => date("Y-m-d H:i",$obj_Directory->getATime()),
                                                                    'dateUpdate'=> date("Y-m-d H:i",$obj_Directory->getMTime()),
                                                                    'size'      => filesize($file),//$obj_Directory->getSize(),
                                                                    'hash'      => sha1_file($file),
                                                                    'name'      => substr($file, strripos($file, DIRECTORY_SEPARATOR )+1));
                }
            }
        }
        ksort($this->_resultados);
    }
 
    /**
     * Metodo _listarDirectorios.
     * Genera el arreglo de resultados correspondiente solo a directorios.
     * @return void
     */
    public function _listarDirectorios()
    {
        $objIterator = new \RecursiveIteratorIterator($obj_Directory = new \RecursiveDirectoryIterator($this->_dirBase),
                                                     \RecursiveIteratorIterator::SELF_FIRST);
        $objIterator->setMaxDepth($this->_profundidadEscaneo);
        // Recorro los directorios.
        foreach ($objIterator as $file) {
            if (is_dir($file)) {
                $this->_resultados[$file->__toString()] = array('date'      => date("d-m-Y H:i",$obj_Directory->getATime()),
                                                                'dateUpdate'=> date("d-m-Y H:i",$obj_Directory->getMTime()));
            }
        }
    }
 
    /**
     * Metodo _listarTodo.
     * Genera el arreglo de resultados correspondiente a directorios y archivos.
     * @return void
     */
    public function _listarTodo()
    {
        $this->_listarDirectorios();
        $this->_listarArchivos();
        ksort($this->_resultados);
    }
 
    /**
     * Metodo getNombresArchivos.
     * Retorna solo los nombre de archivos.
     * @return array
     */
    public function getNombresArchivos()
    {
        $objIterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->_dirBase),
                                                     \RecursiveIteratorIterator::SELF_FIRST);
        $objIterator->setMaxDepth($this->_profundidadEscaneo);
        // Recorro los Archivos.
        foreach ($objIterator as $file) {
            if (is_file($file)) {
                $ext        = substr($file, strripos($file, '.')+1);
                if (in_array($ext, $this->_tipoArchivos)) {
                    $this->_resultados[] = substr($file, strripos($file, DIRECTORY_SEPARATOR )+1);
                }
            }
        }
        return $this->_resultados;
    }
}
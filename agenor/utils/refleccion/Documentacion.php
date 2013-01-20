<?php
namespace agenor\utils\refleccion;
/**
 * Clase Documentacion.
 * Proporciona datos sobre la documentacion de Metodos.
 *
 * @package     refleccion creado en el projecto agenor
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (19/01/2013 - 19/01/2013).
 */
class Documentacion extends \agenor\utils\refleccion\Refleccion
{
    /**
     * Constructor de Clase.
     * Solo realiza la instancia de la Clase Refleccion.
     * @param   string $clase Tipo Namespace.
     * @return  void
     */
    public function __construct($clase)
    {
        parent::__construct(null, $clase);
    }
    
    /**
     * Retorna un arreglo con la documentacion de la Clase.
     * @return array
     */
    public function getComentsClass()
    {
        $descripcion    = explode(' @', parent::getComents());
        $retorno        = array();
        $arrBusq        = array('(\*/)' ,'(\*)' ,'(\n)' ,'(\r)' ,'(\r\n)'   ,'(\s+)');
        $arrRemp        = array(''      , ''    ,' '    , ' '   ,' '        ,' ' );
        foreach ($descripcion as $valor) {
            $primEspacio    = stripos($valor, ' ');
            $titulo         = substr($valor, 0, $primEspacio);
            $titulo         = (strpos($titulo,'/**') === 0)? 'title' : $titulo;
            $comentario     = preg_replace($arrBusq, $arrRemp, substr(array_shift($descripcion), $primEspacio));
            $retorno[$titulo]        =trim($comentario);
        }
     
        return $retorno;
    }
    
    /**
     * Retorna un arreglo con la documentacion del Metodo.
     * @param \ReflectionMethod $metodo Nombre del Metodo a Analizar.
     * @return array
     */
    public function getComentMethod(\ReflectionMethod $metodo)
    {
        $retorno        = array();
        $descripcion    = explode(' @', parent::getMethodComents($metodo));
        $arrBusq        = array('(\*/)' ,'(\*)' ,'(\n)' ,'(\r)' ,'(\r\n)'   ,'(\s+)');
        $arrRemp        = array(''      , ''    ,' '    , ' '   ,' '        ,' ' );
        
        foreach ($descripcion as $valor) {
            $primEspacio    = stripos($valor, ' ');
            $titulo         = substr($valor, 0, $primEspacio);
            $titulo         = (strpos($titulo,'/**') === 0)? 'title' : $titulo;
            $comentario     = preg_replace($arrBusq, $arrRemp, substr(array_shift($descripcion), $primEspacio));
            $comentario     = trim($comentario);
            if ($titulo == 'param') {
                // Armo el Arreglo de Parametros
                $parametros = explode(' ', $comentario);
                $type       = array_shift($parametros);
                $var        = array_shift($parametros);
                $comment    = implode(' ',$parametros);
                $name       = substr($var, 1);
                                
                $retorno['param'][$name]    = array('type'      => $type,
                                                    'var'       => $var,
                                                    'comment'   => $comment);
            } else {
                $retorno[$titulo]        =trim($comentario);
            }
        }
        return $retorno;        
    }
}
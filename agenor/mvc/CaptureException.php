<?php
namespace agenor\mvc;

/**
 * Clase CaptureExeption.
 * Captura Los Errores que se Puedan Producir en la Libreria.
 *
 * @package     MVC creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     1.0.0 (10/05/2012 - 10/05/2012)
 */
class CaptureException extends \Exception // la \ es para posicionarse en el espacio global
{
    /**
     * Es el Manejador de Errores, los errores son capturadas por este metodo,
     * y se lanza una excepcion.
     * 
     * @param integer   $errorNumero    Numero de Error.
     * @param string    $errorMsj       Mensaje del Error que se produjo.
     * @param string    $errorArchivo   Nombre del Archivo en donde se genero el error.
     * @param integer   $errorLinea     Numero de Linea donde se genero el Error.
     * @throws lanza una excepcion.
     */
    public static function errorHandler($errorNumero, $errorMsj, $errorArchivo, $errorLinea)
    {
        $error = new self();
        $error->message     = $errorMsj;
        $error->code        = $errorNumero;
        $error->file        = $errorArchivo;
        $error->line        = $errorLinea;
        
        // Como es un Error y no una Excepcion lanzo la Excepcion.
        throw $error;
       
        /* No ejecutar el gestor de errores interno de PHP */
        return true; 
    }
    
    /**
     * Captura una Excepcion para proceder a ver como se mostrara la misma.
     * @param   \Exception $e   Excepcion que contiene los datos
     * @return  boolean         Siempre Retorna True, pues de lo contrario se 
     *                          lanza el gestor de errores de php y eso no lo
     *                          queremos.
     */
    public static function exceptionHandler(\Exception  $e)
    {
        if (Registry::get('environment') == 'debug') {
            echo '<pre>';
            echo '<hr>';
            echo 'Codigo  : ' . $e->getCode() . '</br>';
            echo 'Archivo : ' . $e->getFile() . '</br>';
            echo 'Linea   : ' . $e->getLine() . '</br>';
            echo 'Mensaje : ' . $e->getMessage() . '</br>';
            echo 'Previo  : ' . $e->getPrevious() . '</br>';
            //echo 'Trace   : ' . $e->getTrace() . '</br>';
            echo 'Trace S.: ' . $e->getTraceAsString() . '</br>';
            echo '<hr>';
            echo '</pre>';
        }
        
        //self::registrarLog($e);
        // Aqui tendria que cambiar el Controlador al de Error 
        // Que Muestre Pantalla de Error
        // Ver si existe el Resource log o no y si es en base o archivo etc ver adapters

        
        /* No ejecutar el gestor de errores interno de PHP */
        return true; 
    }
    
    public static function registrarLog(\Exception $ex)
    {
        $log    = \agenor\mvc\Registry::get('application')->log;

        $stat   = isset($log->algo)? $log->state : 1;
        if ($stat == 1) {
            $dir    = isset($log->dir)? $log->dir : 'log';
            $pref   = isset($log->preg)? $log->pref : 'agenor';
        
            $ruta           = DIR_BASE . $dir . DS ;
            $archivo        = $pref . '_' . date('Y-m-d') . '.txt';
         
            $string         = "ERROR : " . date('Y-m-d H:i:s') . " :\r\n"
                            . "========================================\r\n"
                            . "Codigo  : " . $ex->getCode() . "\r\n"
                            . "Archivo : " . $ex->getFile() . "\r\n"
                            . "Linea   : " . $ex->getLine() . "\r\n"
                            . "Mensaje : " . $ex->getMessage() . "\r\n"
                            . "Previo  : " . $ex->getPrevious() . "\r\n"
                            //. "Trace   : " . var_dump($ex->getTrace()) . "\r\n"
                            . "Trace S.: " . $ex->getTraceAsString() . "\r\n"
                            . "========================================\r\n";
            
            $fp             = fopen($ruta . $archivo, "a+");
            if ($fp) {
                fputs ($fp, $string);
            }
            fclose ($fp);
        }
        //echo '<pre>';
        //echo $string; // Borrar en el definitivo
        //echo '</pre>';
    }
    
    
    
    /**
     * Presenta el Error en Formato HTML con el Helper del Sistema. 
     */
    private function _helperError()
    {
        switch ($this->error->code) {
            case E_USER_ERROR:
                echo "<b>ERROR DE USUARIO:</b> [$this->error->code] $this->error->message<br />\n";
                echo "  Error fatal en la línea $this->error->line en el archivo $this->error->file";
                echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
                break;

            case E_USER_WARNING:
                echo "<b>ADBERTENCIA</b> [$this->error->code] $this->error->message<br />\n";
                break;

            case E_USER_NOTICE:
                echo "<b>NOTICIA</b> [$this->error->code] $this->error->message<br />\n";
                break;

            default:
                echo "ERROR DESCONOCIDO: [$this->error->code] $this->error->message<br />\n";
                break;
        }
    }
    
    
    
}

<?php
namespace agenor\security;

/**
 * Clase Session.
 * Se encarga de Otorgar una interfaz orientada a Objetos para una session.
 *
 * @package     MVC creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.2.0 (02/10/2012 - 15/01/2013)
 *              0.2.0 Se agrego funciones de seguridad.
 */
class Session
{   
    /**
     * Nos informa si se trata de una nueva session o si continua con una session anterior.
     * @var boolean 
     */
    private static $_nuevaSession = false;
    
    /**
     * Comienza una session en la peticion actual o la mantiene.
     * @param   integer     $time   Tiempo en minutos maximo de inactividad.
     */
    public static function start($url = '', $time = 60)
    {
        self::_seguridad();
        session_start();
        if (self::isRegistered('SESSION_TIME_CONTROL')) {
            self::isActiva();
            $_SESSION['SESSION_TIME_CONTROL']['START']      = time();
        } else {
            self::$_nuevaSession = true;
            // Instante de tiempo en que se creo la session
            $_SESSION['SESSION_TIME_CONTROL']['START']      = time();
            // Duracion de la Session
            $_SESSION['SESSION_TIME_CONTROL']['DURATION']   = (int) $time;
            // Url donde se enviara cuando expire la misma
            $_SESSION['SESSION_TIME_CONTROL']['URL']        = $url;
        }
    }
    
    /**
     * Configura el Tiempo de Duraccion de una Session-
     * @param   integer   $time   Cantidad de minutos en que expirara la Session.
     * @return  void
     */
    public static function expire($time)
    {
        if (is_int($time)) {
            $_SESSION['SESSION_TIME_CONTROL']['DURATION'] = (int) $time;
        }
        
    }
    
    /**
     * Verifica si la session esta activa en caso de no serlo la destruye.
     */
    private static function isActiva()
    {
        if ( (time() - $_SESSION['SESSION_TIME_CONTROL']['START']) > $_SESSION['SESSION_TIME_CONTROL']['DURATION'] * 60 ) {
            Session::destroy();
            if ($_SESSION['SESSION_TIME_CONTROL']['URL']) {
                header('location:' . $_SESSION['SESSION_TIME_CONTROL']['URL']);
            } else {
                header('location:' . URL_HTTP . URL_BASE);
            }
        }
    }

    /**
     * Verifica si un nombre de session esta registrada/iniciada o no.
     * @param   string  $clave Nombre de la Session
     * @return  boolean
     */
    public static function isRegistered($clave)
    {
        return isset($_SESSION[$clave]);
    }
    
    /**
     * Setea un Espacio de Session.
     * @param   string  $clave Nombre del Espacio de Session
     * @param   string  $valor  Valor para el Espacio de session.
     * @return  void
     */
    public static function set($clave, $valor)
    {
        if (self::$_nuevaSession) {
            // Para Aumentar la Seguridad al Autenticarse.
            session_regenerate_id(true);
            self::$_nuevaSession = false;
        }
        $_SESSION[$clave] = $valor;
    }
    
    /**
     * Retorna una clave de session.
     * @param   string/array  $clave  Clave que se desea obtener el valor.
     * @return  mixed           Valor de la clave de session.
     */
    public static function get($clave)
    {
        if (self::isRegistered($clave)) {
            return $_SESSION[$clave];
        }
    }
    
    /**
     * Destruye un espacio de session o la session completa.
     * @param   mixed   $clave Nombre del espacio de session o false para
     *                         eliminar toda la session.
     * @return  void
     */
    public static function destroy($clave = false)
    {
        if ($clave) {
            if (is_array($clave)) {
                foreach ($clave as $claveSession) {
                    if ($this->isRegistered($claveSession)) {
                        unset($_SESSION[$claveSession]);
                    }
                }
            } else {
                if ($this->isRegistered($clave)) {
                    unset($_SESSION[$clave]);
                }               
            }
        } else {
            session_destroy();
            session_unset();
            return;
            //header('location:' . URL_HTTP . URL_BASE);
        }
   }
   
   private static function _seguridad()
   {
        // Para que no sea capturada por Scripts tipo js.
        ini_set('session.cookie_httponly', 1);
        //Verificando que la directiva este ON
        if(!ini_get('session.cookie_httponly')){
            echo "Active la directiva session.cookie_httponly del php.ini";
            exit;
        }
        //Verificando que la directiva este ON
        ini_set('session.entropy_file', 30);
        if(!ini_get('session.entropy_file')){
        echo "Active la directiva session.entropy_file del php.ini";
        exit;
        }
       
       
       
   }
}

/** EJEMPLO */
/*
 Para obtener un buen funcionamiento debemos de iniciar la session en 
 el bootstrap de la aplicacion.
 Tambien se podria lograr desde un modulo.
    protected function _startSession() {
    agenor\Session::start();
    }
 */


<?php
namespace agenor\security;

/**
 * Clase HuellaDigital.
 * Genera una Huella digital de un Usuario
 *
 */
class HuellaDigital 
{

	/**
	 * Metodo compareHuellas.
	 * Compara la Huella del Usuario Enviada por el Navegador con el Server.
	 * @access 	public
	 * @param	String	$huellaServer	Se le Pasa el Valor de Session Guardado
	 * 					en la Variable $_SESSION["XXXXXXXX"]["huella"]
	 * @return 	Boolean	true si son iguales false si no coinciden.
	 */
	public static function compareHuellas($huellaServer)
	{	$huellaUsuario	= self::datosUsuarios();
		if($huellaServer === $huellaUsuario) {
            return true;
        } else {
            return false;
        }
	}
	
	public static function getHuellaDigital()
	{
        $huella = '';
        try {
            $huella = self::datosUsuarios();
        } catch (\Exception $exc) {
            \agenor\mvc\CaptureException::registrarLog($exc);
        }
        
        return  $huella;
    }
	
	/**
	 * Metodo datos Usuario.
	 * Genera una Huella Digital par un usuario para ser comparada con el 
	 * archivo del Servidor.
	 * @var 	String	$huellaServer	Se le Pasa el Valor de Session Guardado
	 * 					en la Variable $_SESSION["XXXXXXX"]["huella"]
	 * @access private
	 * @return String	Contiene Huella Digital
	 */
	private static function datosUsuarios()
	{	
        $cadenaHuella   = isset($_SERVER['HTTP_CONNECTION'])?       $_SERVER['HTTP_CONNECTION']         : ''; 
        $cadenaHuella  .= isset($_SERVER['HTTP_USER_AGENT'])?       $_SERVER['HTTP_USER_AGENT']         : '';	
		$cadenaHuella  .= isset($_SERVER['GATEWAY_INTERFACE'])?     $_SERVER['GATEWAY_INTERFACE']       : '';
        $cadenaHuella  .= isset($_SERVER['HTTP_ACCEPT'])?           $_SERVER['HTTP_ACCEPT']             : '';
		$cadenaHuella  .= isset($_SERVER['HTTP_ACCEPT_CHARSET'])?   $_SERVER['HTTP_ACCEPT_CHARSET']     : '';  
        $cadenaHuella  .= isset($_SERVER['HTTP_ACCEPT_ENCODING'])?  $_SERVER['HTTP_ACCEPT_ENCODING']    : '';
		$cadenaHuella  .= isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])?  $_SERVER['HTTP_ACCEPT_LANGUAGE']    : '';
        $cadenaHuella  .= isset($_SERVER['HTTP_VIA'])?              $_SERVER['HTTP_VIA']                : '';
        $cadenaHuella  .= self::getRealIP();
		$huellaUser   = hash('ripemd160',$cadenaHuella);
		return $huellaUser;
	}

	private static function getRealIP()
	{	
        if( isset($_SERVER['HTTP_X_FORWARDED_FOR']) &&  $_SERVER['HTTP_X_FORWARDED_FOR'] != '' ) {
            $client_ip = (!empty($_SERVER['REMOTE_ADDR']))? $_SERVER['REMOTE_ADDR'] :
            ((!empty($_ENV['REMOTE_ADDR']))? $_ENV['REMOTE_ADDR'] : "unknown" );
    
            $entries    = split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);
            reset($entries);
            while (list(, $entry) = each($entries)) {
                $entry = trim($entry);
                if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) ) {
                    // http://www.faqs.org/rfcs/rfc1918.html
                    $private_ip = array('/^0\./',
                                        '/^127\.0\.0\.1/',
                                        '/^192\.168\..*/',
                                        '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
                                        '/^10\..*/');
                    $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
                    if ($client_ip != $found_ip) {
                        $client_ip = $found_ip;
                        break;
                    }
                }
            }
  		} else {
            $client_ip = (!empty($_SERVER['REMOTE_ADDR']))? $_SERVER['REMOTE_ADDR']
            : ((!empty($_ENV['REMOTE_ADDR']))? $_ENV['REMOTE_ADDR'] : "unknown" );
   		}
   		return $client_ip;
	}
    
    /**
     * Funcion Secundaria para ser usada en servidores que no funcione GetRealIP
     * @return string
     */
    private static function verIP()
    {  
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    } 
}
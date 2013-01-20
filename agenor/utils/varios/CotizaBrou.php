<?php
namespace agenor\utils\varios;

/**
 * Clase CotizaBrou.
 * Extrae las cotizaciones de la Pagina del Brou.
 * @copyright   2012 - Walter Sosa
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Walter Sosa
 * @since       Agregado Complemento de camptura de Errores Marcelo Castro
 * @link        objetivophp@gmail.com
 * @version     1.0.0 (19/09/2012 - 19/09/2012)
 */
class CotizaBrou
{
    /**
     * Url de donde se sacara la Informacion.
     * @var string
     */
    private $_urlCotizacion = "http://www.brou.com.uy/web/guest/institucional/cotizaciones";
    
    /**
     * Fecha del Dia de la Extraccion.
     * @var string
     */
    private $_fecha;
    
    /**
     * Cantidad de Decimales para las monedas
     * @var integer
     */
    private $_decimal = 2;

    /**
     * Tabla de Cotizaciones
     * @var array
     */
    private $_cotizaciones   = array();
    
    /**
     * Contiene los errores de Parseo del HTML.
     * @var array
     */
    private $_erroresParseo  = array();

    /**
     * Constructor de Clase.
     * @param   string  $url    Si llegara a cambiar la URL podriamos cambiar esta url.
     */
    public function __construct($url = '')
    {
        $this->_urlCotizacion   = ($url)? $url : $this->_urlCotizacion;
        $this->_fecha           = date('Y-m-d');              
    }
    
    /**
     * Retorna las Cotizaciones del Brou.
     * @return array
     */
    public function getCotizaciones()
    {
        // Como los HTML lo hace cualquier persona pueden tener errores de Parseo lo que largara 
        // E_WARNING con esto habilito la captura de errores y que no lanze el E_WARNING.
        libxml_use_internal_errors(true);
        
        $htmlContent    = file_get_contents($this->_urlCotizacion);
        $dom            = new \DOMDocument();
        $dom            ->loadHTML($htmlContent);
        $rows           = array();
        // Cargo los errores si existen
        $this->_erroresParseo    = libxml_get_errors();
        // limpio el buffer de errores.
        libxml_clear_errors();
        $i              = 0;
        
        foreach( $dom->getElementsByTagName( 'tr' ) as $tr ) {
            if($i > 1 && $i < 6) {
                $cells = array();
                $h =0;
                foreach( $tr->getElementsByTagName( 'td' ) as $td ) {
                    if ($h > 0) {
                        $cells[] = (is_numeric($td->nodeValue))? round($td->nodeValue,2): "'".trim($td->nodeValue) ."'";
                    }
                    $h++;
                }
                $rows[] = $cells;
            }
            $i++;
        }
        
        foreach($rows as $fila) {
            list($moneda, $compra, $venta) = $fila;
            $moneda = str_replace("'", '', $moneda);
            $this->_cotizaciones[$moneda] = array('fecha'   => $this->_fecha,
                                                  'moneda'  => $moneda,
                                                  'compra'  => number_format($compra, $this->_decimal, ',', '.'),
                                                  'venta'   => number_format($venta, $this->_decimal, ',', '.'));
        }        
        
        return $this->_cotizaciones;   
    }
    
    /**
     * Retorna el arreglo para una moneda en Particula
     * @param   string  $moneda Moneda de la Cual se quiere saber su cotizacion.
     * @return  array
     */
    public function getCotizacionMoneda($moneda)
    {
        $moneda     = array();
        
        if (isset($moneda, $this->_cotizaciones)) {
            $moneda = $this->_cotizaciones[$moneda];
        }
        
        return $moneda;
    }
    
    /**
     * Configura la Cantidad de Decimales con la cual se presentaran
     * las monedas.
     * @param integer $decimales
     */
    public function setDecimales($decimales)
    {
        if (is_int($decimales)) {
            $this->_decimal = $decimales;
        }
    }
    
    /**
     * Retorna los errores de Parseo.
     * @return array
     */
    public function getErrores()
    {
        return $this->_erroresParseo;
    }
}

    
    


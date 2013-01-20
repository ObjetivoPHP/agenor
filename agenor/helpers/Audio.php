<?php
namespace agenor\helpers;

/**
 * Clase Audio.
 *  Se encarga de crear un Helper para insertar un archivo de Audio.
 *
 * @package     MVC creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (02/10/2012 - 02/10/2012)
 */
class Audio extends Helpers
{
    private $_tipo  = array('mp3'       => 'audio/mpeg',
                            'ogg'       => 'audio/ogg',
                            'wav'       => 'audio/wav');

    /**
     * Contiene los Archivos de Audio que se emitiran.
     * @var array
     */
    private $_file = array();
    
    /**
     * Establece si el Archivo se comienza a reproducir cuando se cargue la pagina o no.
     * @var boolean 
     */
    private $_autoplay = false;
    
    /**
     * Establece si se repite en loop o no un archivo de audio.
     * @var boolean 
     */
    private $_loop = false;
    
    /**
     * Indica que se usen los Controles o no.
     * @var boolean 
     */
    private $_controls = true;
    
    /**
     * Se fija si se precargara o no el Archivo de Datos.
     * preload ="none", "auto", "metadata"
     * @var string 
     */
    private $_preload = 'none';
    
    /**
     * Comentario que se pondra si el Navegador no soporta la etiqueta de audio.
     * @var string 
     */
    private $_comentario = 'Tu Navegador no Acepta la Etiqueta Audio de HTML5.';
    
    /**
     * Establece un identificador de elemento para que se puedan cambiar los controles.
     * @var string
     */
    protected $_id = '';
    
    
    
    public function file($archivo)
    {
        $this->_file[]  = $archivo;
        return $this;
    }
    
    public function autoplay($boolean)
    {
        $this->_autoplay = ($boolean===true)? true : false;
        return $this;
    }

    public function loop($boolean)
    {
        $this->_loop = ($boolean===true)? true : false;
        return $this;
    }
    
    public function controls($boolean)
    {
        $this->_controls = ($boolean===true)? true : false;
        return $this;
    }
    
    public function preload($preload)
    {
        switch ($preload) {
            case 'none':
                $this->_preload = 'none';
                break;
            case 'auto':
                $this->_preload = 'auto';
                break;
            case 'metadata':
                $this->_preload = 'metadata';
                break;
        }
        
        return $this;        
    }
    
    public function setComent($texto)
    {
        $this->_comentario = $texto;
        return $this;
    }
    
    public function setId($nombre)
    {
        $this->_id = $nombre;
        return $this;
    }
    
    public function render()
    {   
        $audio      = '<audio ';
        $audio     .= (trim($this->_id)!='')?   'id="' . $this->_id . '"'   : '';
        $audio     .= ($this->_autoplay)?       ' autoplay '                : '';
        $audio     .= ($this->_loop)?           ' loop '                    : '';
        $audio     .= ($this->_controls)?       ' controls '                : '';
        $audio     .= ' preload="' . $this->_preload . '" >' . "\n";
        
        // Cargo Cada Uno de Los Archivos
        foreach ($this->_file as $archivo) {
            $extencion  = substr($archivo, strrpos($archivo, '.' )+ 1);
            $audio .= '<source src="' . $archivo . '" type="' . $this->_tipo[$extencion] . '" />' . "\n";  
        }
        $audio     .= $this->_comentario . "\n";
        $audio     .= '</audio>' . "\n";
        
        return $audio;
    }
}

/*
     *<audio controls>
    <source src="archivo.ogg" type="audio/ogg" />
    <source src="archivo.mp3" type="audio/mpeg" />
    </audio>
 */
    /*
    <audio id="player" src="archivo.mp3">
</audio>
<div>
    <button onclick="document.getElementById('player').play();">Reproducir</button>
    <button onclick="document.getElementById('player').pause();">Pausa</button>
    <button onclick="document.getElementById('player').volume += 0.1;">Subir Volumen</button>
    <button onclick="document.getElementById('player').volume -= 0.1;">Bajar Volumen</button>
</div>
    */
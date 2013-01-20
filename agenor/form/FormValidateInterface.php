<?php
namespace agenor\form;

/**
 * Interface FormValidate.
 * Todos los tipos de Campos deben Implementar dicha interface,
 * para proporcionar un servicio de validacion del Campo.
 *
 * @package     Form creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (07/10/2012 - 07/10/2012)
 */
interface FormValidateInterface
{
    /**
     * Valida un Valor de Acuerdo al Formulario dado.
     * @param   mixed   $valor  Valor que se Quiere Validar.
     * @return  boolean         true/false
     */
    public function validate($valor);
    
    /**
     * Retorna un Mensaje de Eror.
     * @return string   Mensaje de Error.
     */
    public function getErrors();
    
}


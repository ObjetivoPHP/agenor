<?php
namespace agenor\form\decorators;

/**
 * Clase FormDecorator.
 * Nos ayuda a decorar un Formulario.
 *
 * @package     Form creado en el projecto AGENOR
 * @copyright   2012 - ObjetivoPHP
 * @license     Gratuito (Free) http://www.opensource.org/licenses/gpl-license.html
 * @author      Marcelo Castro (ObjetivoPHP)
 * @link        objetivophp@gmail.com
 * @version     0.1.0 (07/10/2012 - 07/10/2012)
 */
class FormDecorator extends \agenor\form\decorators\FormDecoratorAbstract
{
    private $_form;
    
    public function htmlTag(\agenor\form\Form $form)
    {
        $this->_form = $form;
    }
}


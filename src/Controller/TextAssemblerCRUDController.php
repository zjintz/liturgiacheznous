<?php

namespace App\Controller;

use Sonata\AdminBundle\Controller\CRUDController;

/**
 * \brief      TextAssemblerCrud Controller.
 *
 * \details    Provides mechanisms to assemble the text of each liturgy
 *             from different sources.
 *
 */
class TextAssemblerCRUDController extends CRUDController
{
    public function listAction()
    {
        return $this->render('text_assembler/list.html.twig');
    }
}

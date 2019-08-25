<?php

namespace App\Controller;

use Sonata\AdminBundle\Controller\CRUDController;

use App\Form\LiturgyTextRequestType;
use App\Util\CNBBAssembler;
use App\Util\IgrejaSantaInesAssembler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * \brief      TextAssemblerCrud Controller.
 *
 * \details    Provides mechanisms to assemble the text of each liturgy
 *             from different sources.
 *
 */
class TextAssemblerCRUDController extends CRUDController
{
    public function assembleAction(Request $request)
    {
        $form = $this->createForm(LiturgyTextRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array
            $data = $form->getData();
            return $this->redirectToRoute(
                'assembler_text',
                [
                    'text_format' => $data['text_format'],
                    'source' => $data['source'],
                    'liturgy_date' => $data['liturgy_date']->format('Y-m-d')
                ]
            );
        }
        
        return $this->render(
            'text_assembler/index.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

}

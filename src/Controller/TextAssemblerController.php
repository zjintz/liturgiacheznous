<?php

namespace App\Controller;

use App\Form\LiturgyTextRequestType;
use App\Util\CNBBAssembler;
use App\Util\IgrejaSantaInesAssembler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * \brief      TextAssembler Controller.
 *
 * \details    Provides mechanisms to assemble the text of each liturgy
 *             from different sources.
 *
 */
class TextAssemblerController extends AbstractController
{


    public function listAction()
    {
        return $this->render('text_assembler/list.html.twig');
    }
    
    /**
     * @Route("/assembler/", name="assembler_index")
     */
    public function index(Request $request)
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

    /**
     * @Route("/assembler/text/{text_format}/{source}/{liturgy_date}/",
     * name="assembler_text",
     * defaults={
     *         "text_format": "pdf",
     *         "source": "CNBB"
     *     },
     * requirements={
     *         "text_format": "DOCX|PDF",
     *         "source": "CNBB|Igreja_Santa_Ines"
     *     }
     *)
     */
    public function getText(
        $text_format,
        $source,
        $liturgy_date,
        IgrejaSantaInesAssembler $santaInesAssembler,
        CNBBAssembler $cnbbAssembler
    ) {
        $textAssembler = $santaInesAssembler;
        if ($source === "CNBB") {
            $textAssembler = $cnbbAssembler;
        }
        $docFile = $textAssembler->getDocument($liturgy_date, $text_format);
        if ($docFile === "Not_Found") {
            return $this->render(
                'text_assembler/not_found.html.twig',
                [
                    'source' => $source,
                    'liturgy_date' => $liturgy_date
                ]
            );
        }
        // Send the temporal file as response (as an attachment)
        $response = new BinaryFileResponse($docFile);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            "doc-".$source."_".$liturgy_date.".".$text_format
        );
        return $response;
    }
}

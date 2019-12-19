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
        if ($docFile === "Not_Found" || $docFile === "Error: Invalid_Date" ) {
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

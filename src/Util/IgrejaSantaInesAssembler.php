<?php

namespace App\Util;

use App\Util\AbstractAssembler;
use App\Util\IgrejaSantaInesFilter;

// Include the requires classes of Phpword
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

/**
 * \brief      Assembles documents from the CNBBA source.
 *
 *
 */
class IgrejaSantaInesAssembler extends AbstractAssembler
{

    private $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }
    // Force Extending class to define this method
    protected function genSourceRoute($liturgyDate)
    {
        $miniDate= str_replace("-", "", $liturgyDate);
        $liturgyRoute = "http://www.igrejasantaines.com/liturgia/?h=".$miniDate;
        return $liturgyRoute;
    }

    protected function assemble($data, $format = "rtf")
    {
        $textFilter = new IgrejaSantaInesFilter();
        $litText = $textFilter->filter($data);

        // Create a new Word document
        $phpWord = new PhpWord();

        /* Note: any element you append to a document must reside inside of a Section. */

        // Adding an empty Section to the document...
        $phpWord->addTitleStyle(1, array('bold' => true), array('spaceAfter' => 240));
        $dayTitle = $phpWord->addSection();
        $l1Text = $phpWord->addSection();
        $salmoText = $phpWord->addSection();
        $gospelText = $phpWord->addSection();

        // Adding Text element to the Section having font styled by default...
        $dayTitle->addTitle($litText["dayTitle"], 1);
        $l1Text->addTitle($litText["l1Title"], 1);
        $l1Text->addText($litText["l1Text"]);
        $salmoText->addTitle($litText["salmoTitle"]);
        $salmoText->addText($litText["salmoText"]);
        $gospelText->addTitle($litText["gospelTitle"]);
        $gospelText->addText($litText["gospelText"]);

        // Saving the document

        $writerFormat = 'RTF';
        if ($format === 'pdf'){
            $dompdfPath = $this->projectDir . '/vendor/dompdf/dompdf';
            if (file_exists($dompdfPath)) {
                define('DOMPDF_ENABLE_AUTOLOAD', false);
                Settings::setPdfRenderer(
                    Settings::PDF_RENDERER_DOMPDF,
                    $this->projectDir . '/vendor/dompdf/dompdf'
                );
            }
            $writerFormat = 'PDF';
        }
        $objWriter = IOFactory::createWriter($phpWord, $writerFormat);

        $filePath = $this->projectDir.'/var/cache/generatedDoc.'.$format;
        // Write file into path
        $objWriter->save($filePath);
        return $filePath;
    }
}

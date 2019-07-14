<?php

namespace App\Writer;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

/**
 * \brief      Creates the liturgy document.
 *
 */
class DocumentCreator
{
    protected $assistant;
    
    protected function writeLines($templateProcessor, $psalmLines, $section)
    {
        $blockMark = "psalmLineBlock".$section."#";
        $lineMark = "psalmLine".$section."#";
        $psalmLinesCount =  count($psalmLines);
        $counter = 1;
        foreach ($psalmLines as $line) {
            $templateProcessor->cloneBlock($blockMark.$counter, 1);
            $templateProcessor->setValue($lineMark.$counter, $line);
            $counter++;
        }
        for ($counter; $counter <=10; ++$counter) {
            $templateProcessor->cloneBlock($blockMark.$counter, 0);
        }
        return $templateProcessor;
    }
    protected function addPsalmLines($templateProcessor, $litText)
    {
        $psalmLines = $this->assitant->getPsalmLines(
            $litText->getTemporalSection()->getPsalmReading()->getText()
        );
        $templateProcessor = $this->writeLines($templateProcessor, $psalmLines, "");

        $santoralSection = $litText->getSantoralSection();
        if (!is_null($santoralSection)) {
            if (!is_null($santoralSection->getPsalmReading())) {
                $psalmLines = $this->assitant->getPsalmLines(
                    $santoralSection->getPsalmReading()->getText()
                );
                $templateProcessor = $this->writeLines(
                    $templateProcessor,
                    $psalmLines,
                    "Santoral"
                );
            }
        }
        return $templateProcessor;
    }
    
    public function createDocument($format, $litText, $projDir)
    {
        $this->assitant = new WriterAssistant();
        $template = $this->assitant->selectTemplate($litText, $projDir);
        $templateProcessor = new TemplateProcessor($template);
        $liturgyArray = $this->assitant->getLiturgyArray($litText);
        foreach ($liturgyArray as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }

        $templateProcessor = $this->addPsalmLines($templateProcessor, $litText);


        $filePath = $projDir.'/var/cache/generatedDoc.docx';
        $templateProcessor->saveAs($filePath);
        if ($format === "DOCX") {
            return $filePath;
        }
        return $this->writeDoc($projDir, $filePath);
    }

    protected function writeDoc($projDir, $filePath)
    {
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($filePath);
        $dompdfPath = $projDir . '/vendor/dompdf/dompdf';
        if (file_exists($dompdfPath)) {
            if (!defined('DOMPDF_ENABLE_AUTOLOAD')){
                define('DOMPDF_ENABLE_AUTOLOAD', false);
            }
            Settings::setPdfRenderer(
                Settings::PDF_RENDERER_DOMPDF,
                $projDir . '/vendor/dompdf/dompdf'
            );
        }
        $objWriter = IOFactory::createWriter($phpWord, "PDF");
        $filePath = $projDir.'/var/cache/generatedDoc.pdf';
        // Write file into path
        $objWriter->save($filePath);
        return $filePath;
    }
}

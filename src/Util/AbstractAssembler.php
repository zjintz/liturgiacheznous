<?php

namespace App\Util;

// Include the requires classes of Phpword
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

/**
 * \brief      Base clase for concrete assemblers.
 *
 * \details    This class defines the methos used to get the liturgy texts
 *             and convert them to PDF\RTF documments.
 *
 */
abstract class AbstractAssembler
{

    // Force Extending class to define this method
    abstract protected function genSourceRoute($liturgyDate);
    abstract protected function assemble($data, $format = "rtf");


    
    /**
     * \brief      Common method to get the raw data from a url.
     *
     * \param      $url The source to get the data from.
     *
     * \return     return The html text got from the source.
     */
    protected function getRawContent($url)
    {
        $link = curl_init();
        curl_setopt($link, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($link, CURLOPT_URL, $url);
        $data = curl_exec($link);
        curl_close($link);
        return $data;
    }

    protected function createDocument($format, $litText, $projDir)
    {
                // Create a new Word document
        $phpWord = new PhpWord();

        /* Note: any element you append to a document must reside inside of a Section. */

        // Adding an empty Section to the document...

        

        $titleStyle = 'titleStyle';
        $phpWord->addTitleStyle($titleStyle, array('bold' => true, 'italic' => true, 'size' => 18, 'spaceAfter' => 300, "fgColor"=>"yellow"));

        $titleStyle2 = 'titleStyle2';
        $phpWord->addTitleStyle($titleStyle2, array('bold' => true, 'underline'=> "line",  'size' => 16, 'spaceAfter' => 200));

        $subTitle = 'subTitle';
        $phpWord->addTitleStyle($subTitle, array('bold' => true, 'italic' => true,  'size' => 13, 'spaceAfter' => 200));

        $introStyle = 'introStyle';
        $phpWord->addFontStyle($introStyle, array('italic' => true, 'size' => 13));
        $paragraphIntroStyle = 'pStyle';
        $phpWord->addParagraphStyle($paragraphIntroStyle, array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT, 'spaceAfter' => 200));

        $textFontStyle = 'textFontStyle';
        $phpWord->addFontStyle($textFontStyle, array('size' => 15));
        $salmoFontStyle = 'salmoFontStyle';
        $phpWord->addFontStyle($salmoFontStyle, array('size' => 13, 'italic' => true, 'fgColor'=>'lightgrey'));
        $textStyle = 'textStyle';
        $phpWord->addParagraphStyle($textStyle, array( 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 230));

        $temporalSection = $litText->getTemporalSection();
        
        $dayTitle = $phpWord->addSection();
        $temporal = $phpWord->addSection();
        /*$dayTitle->addTitle($litText->getDayTitle(), $titleStyle);
                $dayTitle->addTextBreak(2);
        $temporal->addTitle('Temporal', $titleStyle);
        $temporal->addTextBreak();
        $temporal->addTitle($temporalSection["l1Title"], $titleStyle2);
        $temporal->addTextBreak(1);
        $temporal->addText($temporalSection["l1Intro"], $introStyle, $paragraphIntroStyle);
        $temporal->addTitle($temporalSection["l1Subtitle"], $subTitle);
        $temporal->addTextBreak(1);
        $temporal->addText($temporalSection["l1Text"], $textFontStyle, $textStyle );
        $temporal->addTitle($temporalSection["salmoTitle"], $titleStyle2);
        $temporal->addTextBreak(1);
        $temporal->addText($temporalSection["salmoChorus"], $salmoFontStyle);
        $temporal->addText($temporalSection["salmoText"], $textFontStyle, $textStyle);
        if ($temporalSection["hasL2"]) {
            $temporal->addTitle($temporalSection["l2Title"],  $titleStyle2);
            $temporal->addTextBreak(1);
            $temporal->addText($temporalSection["l2Intro"], $introStyle, $paragraphIntroStyle);
            $temporal->addTitle($temporalSection["l2Subtitle"], $subTitle);
            $temporal->addTextBreak(1);
            $temporal->addText($temporalSection["l2Text"], $textFontStyle, $textStyle);
        }
        $temporal->addTitle($temporalSection["gospelTitle"],  $titleStyle2);
        $temporal->addTextBreak(1);
        $temporal->addText($temporalSection["gospelIntro"], $introStyle, $paragraphIntroStyle);
        $temporal->addTitle($temporalSection["gospelSubtitle"], $subTitle);
        $temporal->addTextBreak(1);
        $temporal->addText($temporalSection["gospelText"], $textFontStyle, $textStyle);

        $temporal->addTextBreak(2);
        $santoralSection = $litText["santoral"];
        if($santoralSection["status"] === "Success"){


            $santoral = $phpWord->addSection();
            $santoral->addTitle('Santoral', $titleStyle);
            $santoral->addTextBreak();
            $santoral->addTitle($santoralSection["l1Title"],  $titleStyle2);
            $santoral->addTextBreak(1);
            $santoral->addText($santoralSection["l1Intro"], $introStyle, $paragraphIntroStyle);
            $santoral->addTitle($santoralSection["l1Subtitle"], $subTitle);
            $temporal->addTextBreak(1);
            $santoral->addText($santoralSection["l1Text"], $textFontStyle, $textStyle);
            $santoral->addTitle($santoralSection["salmoTitle"],  $titleStyle2);
            $santoral->addTextBreak(1);
            $santoral->addText($santoralSection["salmoChorus"], $salmoFontStyle);
            $santoral->addText($santoralSection["salmoText"], $textFontStyle, $textStyle);
            if ($santoralSection["hasL2"]) {
                $santoral->addTitle($santoralSection["l2Title"],  $titleStyle2);
                $temporal->addTextBreak(1);
                $santoral->addText($santoralSection["l2Intro"], $introStyle, $paragraphIntroStyle);
                $santoral->addTitle($santoralSection["l2Subtitle"], $subTitle);
                $santoral->addTextBreak(1);
                $santoral->addText($santoralSection["l2Text"], $textFontStyle, $textStyle);
            }
            $santoral->addTitle($santoralSection["gospelTitle"],  $titleStyle2);
            $santoral->addTextBreak(1);
            $santoral->addText($santoralSection["gospelIntro"], $introStyle, $paragraphIntroStyle);
            $santoral->addTitle($santoralSection["gospelSubtitle"], $subTitle);
            $santoral->addTextBreak(1);
            $santoral->addText($santoralSection["gospelText"], $textFontStyle, $textStyle);
        }

        // Adding Text element to the Section having font styled by default...
        

        // Saving the document

        $writerFormat = 'RTF';
        if ($format === 'pdf'){
            $dompdfPath = $projDir . '/vendor/dompdf/dompdf';
            if (file_exists($dompdfPath)) {
                define('DOMPDF_ENABLE_AUTOLOAD', false);
                Settings::setPdfRenderer(
                    Settings::PDF_RENDERER_DOMPDF,
                    $projDir . '/vendor/dompdf/dompdf'
                );
            }
            $writerFormat = 'PDF';
        }
        $objWriter = IOFactory::createWriter($phpWord, $writerFormat);
                $filePath = $projDir.'/var/cache/generatedDoc.'.$format;
        // Write file into path
        $objWriter->save($filePath);
        return $filePath;*/
    }

    /**
     * \brief      Common method to get the raw data from a url.
     *
     * \param      $url The source to get the data from.
     *
     * \return     return The html text got from the source.
     */
    public function getDocument($liturgyDate, $format)
    {
        $sourceRoute = $this->genSourceRoute($liturgyDate);
        $rawContent = $this->getRawContent($sourceRoute);
        $document= $this->assemble($rawContent, $format);
        return $document;
    }
}

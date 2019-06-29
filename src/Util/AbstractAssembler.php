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

        $temporalInfo = $litText["temporal"];
        
        $dayTitle = $phpWord->addSection();
        $temporal = $phpWord->addSection();
        $dayTitle->addTitle($litText["dayTitle"], $titleStyle);
        $dayTitle->addTextBreak(2);
        $temporal->addTitle('Temporal', $titleStyle);
        $temporal->addTextBreak();
        $temporal->addTitle($temporalInfo["l1Title"], $titleStyle2);
        $temporal->addTextBreak(1);
        $temporal->addText($temporalInfo["l1Intro"], $introStyle, $paragraphIntroStyle);
        $temporal->addTitle($temporalInfo["l1Subtitle"], $subTitle);
        $temporal->addTextBreak(1);
        $temporal->addText($temporalInfo["l1Text"], $textFontStyle, $textStyle );
        $temporal->addTitle($temporalInfo["salmoTitle"], $titleStyle2);
        $temporal->addTextBreak(1);
        $temporal->addText($temporalInfo["salmoChorus"], $salmoFontStyle);
        $temporal->addText($temporalInfo["salmoText"], $textFontStyle, $textStyle);
        if ($temporalInfo["hasL2"]) {
            $temporal->addTitle($temporalInfo["l2Title"],  $titleStyle2);
            $temporal->addTextBreak(1);
            $temporal->addText($temporalInfo["l2Intro"], $introStyle, $paragraphIntroStyle);
            $temporal->addTitle($temporalInfo["l2Subtitle"], $subTitle);
            $temporal->addTextBreak(1);
            $temporal->addText($temporalInfo["l2Text"], $textFontStyle, $textStyle);
        }
        $temporal->addTitle($temporalInfo["gospelTitle"],  $titleStyle2);
        $temporal->addTextBreak(1);
        $temporal->addText($temporalInfo["gospelIntro"], $introStyle, $paragraphIntroStyle);
        $temporal->addTitle($temporalInfo["gospelSubtitle"], $subTitle);
        $temporal->addTextBreak(1);
        $temporal->addText($temporalInfo["gospelText"], $textFontStyle, $textStyle);

        $temporal->addTextBreak(2);
        $santoralInfo = $litText["santoral"];
        if($santoralInfo["status"] === "Success"){


            $santoral = $phpWord->addSection();
            $santoral->addTitle('Santoral', $titleStyle);
            $santoral->addTextBreak();
            $santoral->addTitle($santoralInfo["l1Title"],  $titleStyle2);
            $santoral->addTextBreak(1);
            $santoral->addText($santoralInfo["l1Intro"], $introStyle, $paragraphIntroStyle);
            $santoral->addTitle($santoralInfo["l1Subtitle"], $subTitle);
            $temporal->addTextBreak(1);
            $santoral->addText($santoralInfo["l1Text"], $textFontStyle, $textStyle);
            $santoral->addTitle($santoralInfo["salmoTitle"],  $titleStyle2);
            $santoral->addTextBreak(1);
            $santoral->addText($santoralInfo["salmoChorus"], $salmoFontStyle);
            $santoral->addText($santoralInfo["salmoText"], $textFontStyle, $textStyle);
            if ($santoralInfo["hasL2"]) {
                $santoral->addTitle($santoralInfo["l2Title"],  $titleStyle2);
                $temporal->addTextBreak(1);
                $santoral->addText($santoralInfo["l2Intro"], $introStyle, $paragraphIntroStyle);
                $santoral->addTitle($santoralInfo["l2Subtitle"], $subTitle);
                $santoral->addTextBreak(1);
                $santoral->addText($santoralInfo["l2Text"], $textFontStyle, $textStyle);
            }
            $santoral->addTitle($santoralInfo["gospelTitle"],  $titleStyle2);
            $santoral->addTextBreak(1);
            $santoral->addText($santoralInfo["gospelIntro"], $introStyle, $paragraphIntroStyle);
            $santoral->addTitle($santoralInfo["gospelSubtitle"], $subTitle);
            $santoral->addTextBreak(1);
            $santoral->addText($santoralInfo["gospelText"], $textFontStyle, $textStyle);
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
        return $filePath;


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

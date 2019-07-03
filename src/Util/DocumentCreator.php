<?php

namespace App\Util;

// Include the requires classes of Phpword
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

/**
 * \brief      Creates the document.
 *
 *
 */
class DocumentCreator
{
    protected $phpWord;

    protected function writeGospel($liturgy, $reading)
    {
        $boldItalicText = 'boldItalicText';
        $this->phpWord->addFontStyle(
            $boldItalicText,
            array(
                'italic' => true,
                'bold' => true,
                'name' => 'TimesNewRoman',
                'size' => 15,
            )
        );

        $italicText = 'italicText';
        $this->phpWord->addFontStyle(
            $italicText,
            array(
                'italic' => true,
                'name' => 'TimesNewRoman',
                'size' => 15,
            )
        );
        $normalText = 'normalText';
        $this->phpWord->addFontStyle(
            $normalText,
            array('name' => 'TimesNewRoman','size' => 15)
        );
        $boldText = 'boldText';
        $this->phpWord->addFontStyle(
            $boldText,
            array(
                'bold' => true,
                'name' => 'TimesNewRoman',
                'size' => 15,
            )
        );

                $titleStyleRed = 'titleStyleRed';
        $this->phpWord->addParagraphStyle(
            $titleStyleRed,
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START)
        );
        $fontTitleRed = 'fontTitleRed';
        $this->phpWord->addFontStyle(
            $fontTitleRed,
            array(
                'name' => 'GoudyHandtooledBT',
                'size' => 16,
                'spaceAfter' => 300,
                'color' => 'CE181E'
            )
        );

        $subtitleStyle = 'subtitleStyle';
        $this->phpWord->addParagraphStyle(
            $subtitleStyle,
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END)
        );
        $fontSubtitle = 'fontSubtitle';
        $this->phpWord->addFontStyle(
            $fontSubtitle,
            array(
                'name' => 'TimesNewRomanPS',
                'size' => 12,
                'spaceAfter' => 300,
                'color' => 'CE181E'
            )
        );

        $introStyle = 'introStyle';
        $this->phpWord->addParagraphStyle(
            $introStyle,
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START)
        );
        $fontIntro = 'fontIntro';
        $this->phpWord->addFontStyle(
            $fontIntro,
            array(
                'name' => 'TimesNewRoman',
                'italic' => true,
                'bold' => true,
                'size' => 15,
            )
        );

        $chorusStyle = 'chorusStyle';
        $this->phpWord->addParagraphStyle(
            $chorusStyle,
            array(
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
                'spaceBefore' => 0
            )
        );
        $fontChorus = 'fontChorus';
        $this->phpWord->addFontStyle(
            $fontChorus,
            array(
                'name' => 'TimesNewRoman',
                'bold' => true,
                'size' => 15,
            )
        );

        $responseStyle = 'responseStyle';
        $this->phpWord->addParagraphStyle(
            $responseStyle,
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END,)
        );
        $fontResponse = 'fontResponse';
        $this->phpWord->addFontStyle(
            $fontResponse,
            array(
                'name' => 'TimesNewRoman',
                'bold' => true,
                'size' => 15,
                'color' => 'CE181E'
            )
        );

        $keepNextStyle = 'keepNextStyle';
        $this->phpWord->addParagraphStyle(
            $keepNextStyle,
            array(
                'keepNext'=> true
            )
        );
        
        $textStyle = 'textStyle';
        $this->phpWord->addParagraphStyle(
            $textStyle,
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH,)
        );
        $fontText = 'fontText';
        $this->phpWord->addFontStyle(
            $fontText,
            array(
                'name' => 'TimesNewRoman',
                'size' => 15,
                'spaceAfter' => 300,
            )
        );
        
        $liturgy->addTextBreak();
        $liturgy->addText($reading->getTitle(), $fontTitleRed,  $titleStyleRed);
        $liturgy->addTextBreak();
        $liturgy->addText("Aclamação ao Evangelho", $fontSubtitle, $titleStyleRed);
        $liturgy->addTextBreak();
        $liturgy->addText("R. Aleluia, aleluia, aleluia.", $boldText);
        $liturgy->addText("L. Deus o mundo tanto amou,", $normalText);
        $liturgy->addText("que lhe deu seu próprio Filho,", $normalText);
        $liturgy->addText("para que todo o que nele crer, ", $normalText);
        $liturgy->addText("encontre vida eterna.", $normalText);
        $liturgy->addTextBreak();
        $subtitle = $reading->getSubtitle();
        $liturgy->addText($reading->getSubtitle(), $fontSubtitle, $subtitleStyle);
        $liturgy->addTextBreak();

        $liturgy->addText("D. Dóminus vobíscum.", $normalText);
        $liturgy->addText("D. O Senhor esteja convosco.", $italicText, $subtitleStyle);
        $liturgy->addText("R. Et cum spíritu tuo.", $boldText);
        $liturgy->addText("R. Ele está no meio de nós.", $boldText, $subtitleStyle);
        $liturgy->addText("D. Léctio Sancti Evangélii se-", $normalText);
        $liturgy->addText("   cúndum Ioánnem.", $normalText);
        $liturgy->addText("D. Proclamação do Evangelho de", $italicText, $subtitleStyle);
        $author = str_replace(
            "Proclamação do Evangelho de Jesus Cristo segundo",
            "",
            $subtitle
        );
        $author = trim($author,"0123456789+-,.");
        $author = trim($author,"0123456789+-,");
        
        $liturgy->addText("Jesus Cristo segundo ".$author.".", $italicText, $subtitleStyle);
        $liturgy->addText("R. Glória tibi, Dómine.", $boldText);
        $liturgy->addText("R. Glória a Vós, Senhor.", $boldText, $subtitleStyle);
        $liturgy->addTextBreak();
        $liturgy->addText($reading->getIntroduction(), $fontIntro, $introStyle);
        $liturgy->addTextBreak();
        $liturgy->addText($reading->getText(), $fontText, $textStyle);
        $liturgy->addTextBreak();
        $liturgy->addText("L.Verbum Dómini.", $normalText);
        $liturgy->addText("L.Palavra da Salvação.", $italicText, $subtitleStyle);
        $liturgy->addText("R.Laus tibi, Christe.", $boldText);
        $liturgy->addText("R.Glória a Vós, Senhor.", $boldItalicText, $subtitleStyle);
        return $liturgy;
    }
    protected function writeReading($liturgy, $reading)
    {
        $titleStyleRed = 'titleStyleRed';
        $this->phpWord->addParagraphStyle(
            $titleStyleRed,
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START)
        );
        $fontTitleRed = 'fontTitleRed';
        $this->phpWord->addFontStyle(
            $fontTitleRed,
            array(
                'name' => 'GoudyHandtooledBT',
                'size' => 16,
                'spaceAfter' => 300,
                'color' => 'CE181E'
            )
        );

        $subtitleStyle = 'subtitleStyle';
        $this->phpWord->addParagraphStyle(
            $subtitleStyle,
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END)
        );
        $fontSubtitle = 'fontSubtitle';
        $this->phpWord->addFontStyle(
            $fontSubtitle,
            array(
                'name' => 'TimesNewRomanPS',
                'size' => 12,
                'spaceAfter' => 300,
                'color' => 'CE181E'
            )
        );

        $introStyle = 'introStyle';
        $this->phpWord->addParagraphStyle(
            $introStyle,
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START)
        );
        $fontIntro = 'fontIntro';
        $this->phpWord->addFontStyle(
            $fontIntro,
            array(
                'name' => 'TimesNewRoman',
                'italic' => true,
                'bold' => true,
                'size' => 15,
            )
        );

        $chorusStyle = 'chorusStyle';
        $this->phpWord->addParagraphStyle(
            $chorusStyle,
            array(
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
                'spaceBefore' => 0
            )
        );
        $fontChorus = 'fontChorus';
        $this->phpWord->addFontStyle(
            $fontChorus,
            array(
                'name' => 'TimesNewRoman',
                'bold' => true,
                'size' => 15,
            )
        );

        $responseStyle = 'responseStyle';
        $this->phpWord->addParagraphStyle(
            $responseStyle,
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END,)
        );
        $fontResponse = 'fontResponse';
        $this->phpWord->addFontStyle(
            $fontResponse,
            array(
                'name' => 'TimesNewRoman',
                'bold' => true,
                'size' => 15,
                'color' => 'CE181E'
            )
        );

        $keepNextStyle = 'keepNextStyle';
        $this->phpWord->addParagraphStyle(
            $keepNextStyle,
            array(
                'keepNext'=> true
            )
        );
        
        $textStyle = 'textStyle';
        $this->phpWord->addParagraphStyle(
            $textStyle,
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH,)
        );
        $fontText = 'fontText';
        $this->phpWord->addFontStyle(
            $fontText,
            array(
                'name' => 'TimesNewRoman',
                'size' => 15,
                'spaceAfter' => 300,
            )
        );
        $liturgy->addTextBreak();

        $liturgy->addText($reading->getTitle(), $fontTitleRed,  $titleStyleRed);
        if ( get_class($reading) === "App\Entity\PsalmReading") {
            $liturgy->addTextBreak();
            $chorus = $reading->getChorus();
            if (substr( $chorus, 0, 2 ) !== "R."){
                $chorus = "R. ".$chorus;
            }
            $liturgy->addText($chorus, $fontChorus, $chorusStyle);
            $liturgy->addTextBreak();
            $lines = explode("R.", trim($reading->getText()));
            foreach ($lines as $line) {
                if(preg_match('/\S/', $line)){ //check is not empty
                    $liturgy->addText($line, $fontText, $textStyle);
                    $liturgy->addText("R.", $fontResponse, $responseStyle);
                }
            }

            return $liturgy;
                    
        }
            //        $liturgy->addText("Primera leitura", $fontTitleRed,  $titleStyleRed);
        $liturgy->addTextBreak();
        $liturgy->addText($reading->getSubtitle(), $fontSubtitle, $subtitleStyle);
        $liturgy->addTextBreak();
        $liturgy->addText($reading->getIntroduction(), $fontIntro, $introStyle);
        $liturgy->addTextBreak();
        $liturgy->addText($reading->getText(), $fontText, $textStyle);
        $liturgy->addTextBreak();
        return $liturgy;
    }
    
    public function createDocument($format, $litText, $projDir)
    {
        $this->phpWord = new PhpWord();
        // Adding an empty Section to the document...
        $titleStyle = 'titleStyle';
        $this->phpWord->addParagraphStyle(
            $titleStyle,
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER)
        );
        $fontTitle = 'fontTitle';
        $this->phpWord->addFontStyle(
            $fontTitle,
            array(
                'name' => 'GoudyHandtooledBT',
                'size' => 18,
                'spaceAfter' => 300,
                'color' => '2F509E'
            )
        );

        $normalText = 'normalText';
        $this->phpWord->addFontStyle(
            $normalText,
            array(
                'name' => 'TimesNewRoman',
                'size' => 15
            )
        );
        $subtitleStyle = 'subtitleStyle';
        $this->phpWord->addParagraphStyle(
            $subtitleStyle,
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END)
        );
        $italicText = 'italicText';
        $this->phpWord->addFontStyle(
            $italicText,
            array(
                'italic' => true,
                'name' => 'TimesNewRoman',
                'size' => 15,
            )
        );
        $boldText = 'boldText';
        $this->phpWord->addFontStyle(
            $boldText,
            array(
                'bold' => true,
                'name' => 'TimesNewRoman',
                'size' => 15,
            )
        );

        $boldItalicText = 'boldItalicText';
        $this->phpWord->addFontStyle(
            $boldItalicText,
            array(
                'italic' => true,
                'bold' => true,
                'name' => 'TimesNewRoman',
                'size' => 15,
            )
        );


        $temporalSection = $litText->getTemporalSection();
        $l1Reading = $temporalSection->getFirstReading();
        $liturgy = $this->phpWord->addSection();
        $liturgy->addText($litText->getDayTitle(), $fontTitle, $titleStyle);
        $liturgy->addTextBreak();
        $liturgy = $this->writeReading($liturgy, $l1Reading);

        $liturgy->addText("L.Verbum Dómini.", $normalText);
        $liturgy->addText("L.Palavra do Senhor.", $italicText, $subtitleStyle);
        $liturgy->addText("R.Deo grátias.", $boldText);
        $liturgy->addText("R.Graças a Deus.", $boldItalicText, $subtitleStyle);
        $liturgy = $this->writeReading($liturgy, $temporalSection->getPsalmReading());
        $liturgy = $this->writeGospel($liturgy, $temporalSection->getGospelReading());
        if (!is_null($temporalSection->getSecondReading())) {
            $liturgy = $this->writeReading($liturgy, $temporalSection->getSecondReading());
        }
        $santoralSection = $litText->getSantoralSection();
        $liturgy->addTextBreak();
        if (!is_null($santoralSection->getFirstReading())) {
            $liturgy = $this->writeReading($liturgy, $santoralSection->getFirstReading());
        }
        if (!is_null($santoralSection->getPsalmReading())) {
            $liturgy = $this->writeReading($liturgy, $santoralSection->getPsalmReading());
        }
        if (!is_null($santoralSection->getGospelReading())) {
            $liturgy = $this->writeReading($liturgy, $santoralSection->getGospelReading());
        }
        if (!is_null($santoralSection->getSecondReading())) {
            $liturgy = $this->writeReading($liturgy, $santoralSection->getSecondReading());
        }

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
        $objWriter = IOFactory::createWriter($this->phpWord, $writerFormat);
                $filePath = $projDir.'/var/cache/generatedDoc.'.$format;
        // Write file into path
        $objWriter->save($filePath);
        return $filePath;
    }
}

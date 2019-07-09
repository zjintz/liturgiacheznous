<?php

namespace App\Writer;

// Include the requires classes of Phpword
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

/**
 * \brief      Creates the RTF or PDF liturgy document.
 *
 */
class DocumentCreator
{
    protected $phpWord;
    protected $boldItalic = 'boldItalic';
    protected $italic = 'italic';
    protected $bold = 'bold';
    protected $normalText = 'normalText';
    protected $subtitleFont = 'subtitleFont';
    protected $redTitleFont = 'redTitleFont';
    protected $responseFont = 'responseFont';
    protected $titleFont = 'titleFont';
    protected $atStartStyle = 'atStartStyle';
    protected $centerStyle = 'centerStyle';
    protected $atEndStyle = 'atEndStyle';
    protected $textStyle = 'textStyle';
    protected $assistant;
    
    public function __construct()
    {
        $this->phpWord = new PhpWord();
        $this->addFonts();
        $this->addStyles();
        $this->assistant = new WriterAssistant();
    }

    protected function writeGospel($liturgy, $reading)
    {
        if (is_null($reading)) {
            return $liturgy;
        }
        $liturgy->addTextBreak();
        $liturgy->addText($reading->getTitle(), $this->redTitleFont, $this->atStartStyle);
        $liturgy->addTextBreak();
        $liturgy->addText("Aclamação ao Evangelho", $this->subtitleFont, $this->atStartStyle);
        $liturgy->addTextBreak();
        $liturgy->addText("R. Aleluia, aleluia, aleluia.", $this->bold);
        $liturgy->addText("L. Deus o mundo tanto amou,", $this->normalText);
        $liturgy->addText("que lhe deu seu próprio Filho,", $this->normalText);
        $liturgy->addText("para que todo o que nele crer, ", $this->normalText);
        $liturgy->addText("encontre vida eterna.", $this->normalText);
        $liturgy->addTextBreak();
        $liturgy->addText($reading->getSubtitle(), $this->subtitleFont, $this->atEndStyle);
        $liturgy->addTextBreak();
        $liturgy->addText("D. Dóminus vobíscum.", $this->normalText);
        $liturgy->addText("D. O Senhor esteja convosco.", $this->italic, $this->atEndStyle);
        $liturgy->addText("R. Et cum spíritu tuo.", $this->bold);
        $liturgy->addText("R. Ele está no meio de nós.", $this->bold, $this->atEndStyle);
        $liturgy->addText("D. Léctio Sancti Evangélii se-", $this->normalText);
        $liturgy->addText("   cúndum Ioánnem.", $this->normalText);
        $liturgy->addText("D. Proclamação do Evangelho de", $this->italic, $this->atEndStyle);
        $author = $reading->getAuthor();
        $liturgy->addText("Jesus Cristo segundo ".$author.".", $this->italic, $this->atEndStyle);
        $liturgy->addText("R. Glória tibi, Dómine.", $this->bold);
        $liturgy->addText("R. Glória a Vós, Senhor.", $this->bold, $this->atEndStyle);
        $liturgy->addTextBreak();
        $liturgy->addText($reading->getIntroduction(), $this->boldItalic, $this->atStartStyle);
        $liturgy->addTextBreak();
        $liturgy->addText($reading->getText(), $this->normalText, $this->textStyle);
        $liturgy->addTextBreak();
        $liturgy->addText("L.Verbum Dómini.", $this->normalText);
        $liturgy->addText("L.Palavra da Salvação.", $this->italic, $this->atEndStyle);
        $liturgy->addText("R.Laus tibi, Christe.", $this->bold);
        $liturgy->addText("R.Glória a Vós, Senhor.", $this->boldItalic, $this->atEndStyle);
        return $liturgy;
    }

    protected function writePsalmReading($liturgy, $reading)
    {
        if (is_null($reading)) {
            return $liturgy;
        }
        $liturgy->addTextBreak();
        $liturgy->addText($reading->getTitle(), $this->redTitleFont, $this->atStartStyle);
        $liturgy->addTextBreak();
        $chorus = $reading->getChorus();
        $liturgy->addText($chorus, $this->bold, $this->atStartStyle);
        $liturgy->addTextBreak();
        $lines = $this->assistant->getPsalmLines($reading->getText());
        foreach ($lines as $line) {
            $liturgy->addText($line, $this->normalText, $this->textStyle);
            $liturgy->addText("R.", $this->responseFont, $this->atEndStyle);
        }
        return $liturgy;
    }
    
    protected function writeReading($liturgySection, $reading)
    {
        if (is_null($reading)) {
            return $liturgySection;
        }
        $liturgyRun = $liturgySection->addTextRun($this->atStartStyle);
        $liturgyRun->addTextBreak();
        $title ="Primeira Leitura";
        $liturgyRun->addText($title, $this->redTitleFont);
        $liturgyRun = $liturgySection->addTextRun($this->atEndStyle);
        $liturgySection->addText($reading->getSubtitle(), $this->subtitleFont, $this->atEndStyle);
        $liturgySection->addTextBreak();
        $liturgySection->addText($reading->getIntroduction(), $this->italic,$this->atEndStyle);
        $liturgySection->addTextBreak();
        $liturgyRun3 = $liturgySection->addTextRun($this->atStartStyle);
        $liturgyRun3->addText($reading->getText(), $this->normalText);

        return $liturgySection;
    }

    protected function writeTitle($section, $litText)
    {
        $section->addText($litText->getDate()->format("d/m/Y"), $this->titleFont, $this->centerStyle);
        $section->addText($litText->getDayTitle(), $this->normalText, $this->centerStyle);
        $section->addTextBreak();
    }
    
    public function createDocument($format, $litText, $projDir)
    {
        $temporalSection = $litText->getTemporalSection();
        $l1Reading = $temporalSection->getFirstReading();
        $liturgySection = $this->phpWord->addSection();
        $this->writeTitle($liturgySection, $litText);
        // Add text run


        $liturgySection = $this->writeReading($liturgySection, $l1Reading);

        $liturgySection->addText("L.Verbum Dómini.", $this->normalText);
        $liturgySection->addText("L.Palavra do Senhor.", $this->italic, $this->atEndStyle);
        $liturgySection->addText("R.Deo grátias.", $this->bold);
        $liturgySection->addText("R.Graças a Deus.", $this->boldItalic, $this->atEndStyle);
        $liturgySection = $this->writePsalmReading(
            $liturgySection,
            $temporalSection->getPsalmReading()
        );
        $liturgySection = $this->writeGospel($liturgySection, $temporalSection->getGospelReading());
        $liturgySection = $this->writeReading($liturgySection, $temporalSection->getSecondReading());
        
        $santoralSection = $litText->getSantoralSection();
        $liturgySection->addTextBreak();
        $liturgySection = $this->writeReading($liturgySection, $santoralSection->getFirstReading());
        $liturgySection = $this->writePsalmReading(
            $liturgySection,
            $santoralSection->getPsalmReading()
        );
        $liturgySection = $this->writeReading($liturgySection, $santoralSection->getGospelReading());
        $liturgySection = $this->writeReading($liturgySection, $santoralSection->getSecondReading());

        $filePath = $this->writeDoc($format, $projDir);
        return $filePath;
    }

    protected function writeDoc($format, $projDir)
    {
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
    
    protected function addFonts()
    {
        $this->phpWord->addFontStyle(
            $this->boldItalic,
            array(
                'italic' => true,
                'bold' => true,
                'name' => 'TimesNewRoman',
                'size' => 18
            )
        );
        $this->phpWord->addFontStyle(
            $this->italic,
            array('italic' => true,'name' => 'TimesNewRoman','size' => 18)
        );
        $this->phpWord->addFontStyle(
            $this->normalText,
            array('name' => 'TimesNewRoman','size' => 18)
        );
        $this->phpWord->addFontStyle(
            $this->bold,
            array('bold' => true, 'name' => 'TimesNewRoman','size' => 18)
        );
        $this->phpWord->addFontStyle(
            $this->subtitleFont,
            array(
                'name' => 'TimesNewRoman',
                'size' => 18,
                'color' => 'CE181E'
            )
        );
        $this->phpWord->addFontStyle(
            $this->redTitleFont,
            array(
                'name' => 'TimesNewRoman',
                'size' => 18,
                'bold' => true,
                'color' => 'CE181E'
            )
        );
        $this->phpWord->addFontStyle(
            $this->responseFont,
            array(
                'name' => 'TimesNewRoman',
                'bold' => true,
                'size' => 18,
                'color' => 'CE181E'
            )
        );
        $this->phpWord->addFontStyle(
            $this->titleFont,
            array(
                'name' => 'TimesNewRoman',
                'size' => 18,
                'spaceAfter' => 300,
                'color' => '2F509E'
            )
        );
    }

    protected function addStyles()
    {
        $this->phpWord->addParagraphStyle(
            $this->centerStyle,
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER)
        );
        $this->phpWord->addParagraphStyle(
            $this->atEndStyle,
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END)
        );
        $this->phpWord->addParagraphStyle(
            $this->atStartStyle,
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START)
        );
        $this->phpWord->addParagraphStyle(
            $this->textStyle,
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH,)
        );
    }
}

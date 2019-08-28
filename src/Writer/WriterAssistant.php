<?php

namespace App\Writer;

/**
 * \brief This class does some functions to assist the DocumentCreator.
 *
 */
class WriterAssistant
{
    public function getPsalmLines($text)
    {
        $lines = array();
        $preLines = explode("R.", trim($text));
        foreach ($preLines as $line) {
            if (preg_match('/\S/', $line)) { //check is not empty
                $lines[] = trim($line);
            }
        }
        return $lines;
    }

    protected function hasSantoralSection($litText)
    {
        $santoralSection = $litText->getSantoralSection();
        if (!is_null($santoralSection)) {
            if ($santoralSection->getLoadStatus() === "Success") {
                return true;
            }
        }
        return false;
    }
    protected function hasSecondReading($section)
    {
        return !is_null($section->getSecondReading());
    }
    
    protected function isBasicLiturgy($litText)
    {
        $temporalSection = $litText->getTemporalSection();
        if($this->hasSantoralSection($litText)){
            return false;
        }
        if ($this->hasSecondReading($temporalSection)) {
            return false;
        }
        return true;
    }
    
    public function selectTemplate($litText, $projDir)
    {
        $temporalSection = $litText->getTemporalSection();
        $santoralSection = $litText->getSantoralSection();
        if ($this->isBasicLiturgy($litText)) {
            return $projDir.'/templates/liturgy/basic_liturgy.docx';
        }
        if ($this->hasSecondReading($temporalSection)) {
            if (!$this->hasSantoralSection($litText)) {
                return $projDir.'/templates/liturgy/double_reading_liturgy.docx';
            }
        }
        // hasta aca las liturgias SIN SANTORAL

        if (!$this->hasSecondReading($temporalSection)) {
            if (is_null($santoralSection->getPsalmReading())) {
                return $projDir.'/templates/liturgy/single_santoral_liturgy.docx';

            }
            return $projDir.'/templates/liturgy/full_santoral_liturgy.docx';
        }
        // hasta aca las liturgias CON santoral de una sola lectura.
        if (is_null($santoralSection->getPsalmReading())) {
            return $projDir.'/templates/liturgy/single_santoral_2l_liturgy.docx';
        }

        return $projDir.'/templates/liturgy/full_santoral_2l_liturgy.docx';
    }

    public function getLiturgyArray($litText)
    {
        $latinName = [
            "Mateus" => "Matthǽum",
            "João" => "Ioánnem",
            "Marcus" => "Marcum",
            "Lucas" => "Lucam"
        ];
        
        $temporalSection = $litText->getTemporalSection();
        $gospelAcclamation = $litText->getGospelAcclamation();
        $l1Reading = $temporalSection->getFirstReading();
        $psalmReading = $temporalSection->getPsalmReading();
        $gospelReading = $temporalSection->getGospelReading();
        $litArray = [];
        $litArray['dateTitle'] = $litText->getDate()->format("d/m/Y");
        $litArray['liturgyDateTitle'] =  $litText->getDayTitle();
        $litArray['gospelAcclamationRef'] =  $gospelAcclamation->getReference();
        $litArray['gospelAcclamationVerse'] =  $gospelAcclamation->getVerse();
        $litArray['l1Reference'] =  $l1Reading->getReference();
        $litArray['l1Introduction'] =  $l1Reading->getIntroduction();
        $litArray['l1Book'] =  $l1Reading->getBookName();
        $litArray['l1Text'] =  $l1Reading->getText();
        $litArray['psalmReference'] =  $psalmReading->getReference();
        $litArray['psalmChorus'] =  $psalmReading->getChorus();
        $litArray['gospelReference'] =  $gospelReading->getReference();
        $litArray['gospelAuthor'] =  $gospelReading->getAuthor();
        $litArray['gospelAuthorLatin'] = $latinName[$gospelReading->getAuthor()];
        $litArray['gospelIntroduction'] =  $gospelReading->getIntroduction();
        $litArray['gospelText'] =  $gospelReading->getText();
        if (!is_null($temporalSection->getSecondReading())) {
            $l2Reading = $temporalSection->getSecondReading();
            $litArray['l2Reference'] =  $l2Reading->getReference();
            $litArray['l2Introduction'] =  $l2Reading->getIntroduction();
            $litArray['l2Book'] =  $l2Reading->getBookName();
            $litArray['l2Text'] =  $l2Reading->getText();
        }
        $santoralSection = $litText->getSantoralSection();
        if (!is_null($santoralSection)) {
            
            if( $santoralSection->getLoadStatus() !=="Not_Found") {
                $l1Reading = $santoralSection->getFirstReading();
                $litArray['l1ReferenceSantoral'] =  $l1Reading->getReference();
                $litArray['l1IntroductionSantoral'] =  $l1Reading->getIntroduction();
                $litArray['l1BookSantoral'] =  $l1Reading->getBookName();
                $litArray['l1TextSantoral'] =  $l1Reading->getText();
                $psalmReading = $santoralSection->getPsalmReading();
                if (!is_null($psalmReading)) {
                    $litArray['psalmReferenceSantoral'] =  $psalmReading->getReference();
                    $litArray['psalmChorusSantoral'] =  $psalmReading->getChorus();

                }
                $gospelReading = $santoralSection->getGospelReading();
                if (!is_null($gospelReading)){
                    $litArray['gospelReferenceSantoral'] =  $gospelReading->getReference();
                    $litArray['gospelAuthorSantoral'] =  $gospelReading->getAuthor();
                    $litArray['gospelAuthorLatinSantoral'] = $latinName[$gospelReading->getAuthor()];
                    $litArray['gospelIntroductionSantoral'] =  $gospelReading->getIntroduction();
                    $litArray['gospelTextSantoral'] =  $gospelReading->getText();
                }
            }
        }
        
        return $litArray;
    }
        
}

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

    public function selectTemplate($litText, $projDir)
    {
        $temporalSection = $litText->getTemporalSection();
        $santoralSection = $litText->getSantoralSection();
        if (is_null($temporalSection->getSecondReading())) {
            if (is_null($santoralSection)) {
                return $projDir.'/templates/liturgy/basic_liturgy.docx';
            }
            if (is_null($santoralSection->getPsalmReading())) {
                return $projDir.'/templates/liturgy/single_santoral_liturgy.docx';
            }
            return $projDir.'/templates/liturgy/full_santoral_liturgy.docx';
        }

        if (is_null($santoralSection)) {
            return $projDir.'/templates/liturgy/double_reading_liturgy.docx';          
        }
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
        $l1Reading = $temporalSection->getFirstReading();
        $psalmReading = $temporalSection->getPsalmReading();
        $gospelReading = $temporalSection->getGospelReading();
        $litArray = [];
        $litArray['dateTitle'] = $litText->getDate()->format("d/m/Y");
        $litArray['liturgyDateTitle'] =  $litText->getDayTitle();
        $litArray['l1Reference'] =  $l1Reading->getReference();
        $litArray['l1Introduction'] =  $l1Reading->getIntroduction();
        $litArray['l1Book'] =  "Genesis";
        $litArray['l1Text'] =  $l1Reading->getText();
        $litArray['psalmReference'] =  $psalmReading->getReference();
        $litArray['psalmChorus'] =  $psalmReading->getChorus();
        $litArray['gospelReference'] =  $gospelReading->getReference();
        $litArray['gospelAuthor'] =  $gospelReading->getAuthor();
        $litArray['gospelAuthorLatin'] = $latinName[$gospelReading->getAuthor()];
        $litArray['gospelIntroduction'] =  $gospelReading->getIntroduction();
        $litArray['gospelText'] =  $gospelReading->getText();
        if (!is_null($temporalSection->getSecondReading())) {
            $l2Reading = $temporalSection->getFirstReading();
            $litArray['l2Reference'] =  $l2Reading->getReference();
            $litArray['l2Introduction'] =  $l2Reading->getIntroduction();
            $litArray['l2Book'] =  "Genesis";
            $litArray['l2Text'] =  $l2Reading->getText();
        }
        if (!is_null($litText->getSantoralSection())) {
            $santoralSection = $litText->getSantoralSection();
            $l1Reading = $santoralSection->getFirstReading();
            $litArray['l1ReferenceSantoral'] =  $l1Reading->getReference();
            $litArray['l1IntroductionSantoral'] =  $l1Reading->getIntroduction();
            $litArray['l1BookSantoral'] =  "Genesis";
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
        
        return $litArray;
    }
        
}

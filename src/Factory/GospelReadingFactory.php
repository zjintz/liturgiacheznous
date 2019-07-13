<?php

namespace App\Factory;

use App\Entity\Reading;
use App\Entity\GospelReading;

/**
 * \brief a Factory for GospelReading objects.
 *
 *
 */
class GospelReadingFactory extends ReadingFactory
{
    public function createReading(
        $title = "",
        $text = "",
        $intro = "",
        $subtitle = ""
    ) :Reading {
        $gospelReading = new GospelReading();
        $gospelReading->setTitle($title);
        $gospelReading->setText($text);
        $gospelReading->setIntroduction($intro);
        $gospelReading->setSubtitle($subtitle);
        $gospelReading->setAuthor($this->extractAuthor($subtitle));
        $gospelReading->setReference($this->extractReference($title));   
        return $gospelReading;
    }

    private function extractAuthor($subtitle)
    {
        $saoJoao = "João";
        $saoLucas = "Lucas";
        $saoMateus = "Mateus";
        $saoMarcus = "Marcus";
        
        if ($this->isTheAuthor($subtitle, $saoJoao)) {
            return $saoJoao;
        }
        if ($this->isTheAuthor($subtitle, $saoLucas)) {
            return $saoLucas;
        }
        if ($this->isTheAuthor($subtitle, $saoMateus)) {
            return $saoMateus;
        }
        return $saoMarcus;
    }
    
    private function isTheAuthor($subtitle, $candidate)
    {
        $pos = strpos($subtitle, $candidate);
        if ($pos === false) {
            return false;
        }
        return true;
    }
}

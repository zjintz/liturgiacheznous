<?php

namespace App\Factory;

use App\Entity\Reading;
use App\Entity\PsalmReading;

/**
 * \brief a Factory for PsalmReading objects.
 *
 *
 */
class PsalmReadingFactory extends ReadingFactory
{
    public function createReading(
        $title = "",
        $text = "",
        $intro = "",
        $subtitle = ""
    ) :Reading {
        $psalmReading = new PsalmReading();
        $psalmReading->setTitle($title);
        $psalmReading->setChorus($intro);
        $psalmReading->setText($text);
        
        return $psalmReading;
    }
}

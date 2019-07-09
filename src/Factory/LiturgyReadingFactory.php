<?php

namespace App\Factory;

use App\Entity\Reading;
use App\Entity\LiturgyReading;

/**
 * \brief a Factory for LiturgyReading objects.
 *
 *
 */
class LiturgyReadingFactory extends ReadingFactory
{
    public function createReading(
        $title = "",
        $text = "",
        $intro = "",
        $subtitle = ""
    ) :Reading {
        $reading = new LiturgyReading();
        $reading->setTitle($title);
        $reading->setText($text);
        $reading->setIntroduction($intro);
        $reading->setSubtitle($subtitle);
        $reading->setReference($this->extractReference($title));
        
        return $reading;
    }
}

<?php

namespace App\Util;

use App\Entity\LiturgyText;
use Symfony\Component\DomCrawler\Crawler;

/**
 * \brief   An Abstract base filter.
 *
 *
 */
abstract class AbstractFilter
{
    abstract protected function isValidDate($crawler);
    abstract protected function getDayTitle($crawler):string;
    abstract protected function getTemporalText($crawler);
    abstract protected function getSantoralText($crawler);
   
    public function filter($data, $liturgyDate)
    {
        $litText = new LiturgyText();
        if (!$data) {
            $litText->setLoadStatus("Error: No_Data_Found");
            return $litText;
        }
        $crawler = new Crawler($data);
        if (!$this->isValidDate($crawler)) {
            $litText->setLoadStatus("Error: Invalid_Date");
            return $litText;
        }
        try{
            $litText->setDate(new \DateTime($liturgyDate));
            $litText->setDayTitle($this->getDayTitle($crawler));
            $litText->setTemporalSection($this->getTemporalText($crawler));
            $litText->setSantoralSection($this->getSantoralText($crawler));
            $litText->setLoadStatus("Success");
            return $litText;
        }
        catch(\Exception $e){
            $litText->setLoadStatus("Not_Found");
            return $litText;
        }
        

    }
}

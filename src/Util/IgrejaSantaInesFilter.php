<?php

namespace App\Util;

use App\Entity\LiturgyText;
use App\Entity\LiturgySection;
use App\Entity\LiturgyReading;
use App\Entity\PsalmReading;
use Symfony\Component\DomCrawler\Crawler;

/**
 * \brief      Filters raw Data from the Igreja Santa Ines source.
 *
 *
 */
class IgrejaSantaInesFilter
{

    protected function isValidDate($crawler)
    {
        $checkFoundCrawler = $crawler->filter(
            'section.post-content div.czr-wp-the-content'
        );
        if ($checkFoundCrawler->count()) {
            if (trim($checkFoundCrawler->first()->text()) === "DIA INDEFINIDO") {
                return false;
            }
        }
        return true;
    }

    protected function getSection($crawler, $name)
    {
        $section = new LiturgySection();
        $section->setLoadStatus("Success");

        $l1Title = $crawler->filter('div.'.$name.' button.accordion')->first()->html();
        $l1Title = str_replace("<br>", " ", $l1Title);
        $l1Subtitle = $crawler->filter('div.'.$name.' div.panel div.cit_direita')->first()->text();
        $l1Intro = trim($crawler->filter('div.'.$name.' div.panel div.cit_direita_italico')->first()->text());
        $l1Crawler = $crawler->filter('div.'.$name.' div.panel')->first();
        $l1Text = $l1Crawler->filter('span')->each( function (Crawler $node, $i) {
                               return $node->text();
                           }
                           );
        
        $l1Text = implode("", $l1Text);

        $salmoTitle = $crawler->filter('div.'.$name.' button.accordion')->eq(1)->html();
        $salmoTitle = str_replace("<br>", " ", $salmoTitle);
        $gospelTitle = $crawler->filter('div.'.$name.' button.accordion')->last()->html();
        $gospelTitle = str_replace("<br>", " ", $gospelTitle);

        $salmoChorus = trim($crawler->filter('div.'.$name.' div.refrao_salmo span')->first()->text());
        $salmoCrawler = $crawler->filter('div.'.$name.' div.panel.salmo')->children('span, div.refrao_salmo2');

        $salmoText = $salmoCrawler->each( function (Crawler $node, $i) {
                               return $node->text();
                           }
                           );
        $salmoText = implode("", $salmoText);
        $salmoText = str_replace("R.", "\nR.\n", $salmoText);
        $salmoText = trim(str_replace(" \nR.\n ", "\nR.\n", $salmoText));

        $gospelSubtitle = $crawler->filter('div.'.$name.' div.panel div.cit_direita')->last()->text();
        $gospelIntro = trim($crawler->filter('div.'.$name.' div.panel')->last()->filter('div.cit_direita_italico')->first()->text());
        
        $gospelCrawler = $crawler->filter('div.'.$name.' div.panel')->last();
        $gospelText = $gospelCrawler->filter('span')->each( function (Crawler $node, $i) {
                               return $node->text();
                           }
                           );
        $gospelText = implode("", $gospelText);
        $firstReading = $this->makeReading(
            $l1Title,
            $l1Subtitle,
            $l1Intro,
            $l1Text
        );

        $gospelReading = $this->makeReading(
            $gospelTitle,
            $gospelSubtitle,
            $gospelIntro,
            $gospelText
        );
        $psalmReading = new PsalmReading();
        $psalmReading->setTitle($salmoTitle);
        $psalmReading->setChorus($salmoChorus);
        $psalmReading->setText($salmoText);
        $section->setFirstReading($firstReading);
        $section->setPsalmReading($psalmReading);
        $section->setGospelReading($gospelReading);
        
        return $section;
    }

    protected function addL2($crawler, $name, $section)
    {

        $l2Title = $crawler->filter('div.'.$name.' button.accordion')->eq(2)->html();
        $l2Title = str_replace("<br>", " ", $l2Title);
        $l2Subtitle = $crawler->filter('div.'.$name.' div.panel div.cit_direita')->eq(1)->text();
        $l2Intro = trim($crawler->filter('div.'.$name.' div.panel div.cit_direita_italico')->eq(1)->text());
        $l2Crawler = $crawler->filter('div.'.$name.' div.panel')->eq(2);
        $l2Text = $l2Crawler->filter('span')->each( function (Crawler $node, $i) {
                               return $node->text();
                           }
                           );
        $l2Text = implode("", $l2Text);

        $l2Reading = $this->makeReading(
            $l2Title,
            $l2Subtitle,
            $l2Intro,
            $l2Text
        );
        $section->setSecondReading($l2Reading);
        return $section;
    }
    
    protected function getTemporalText($crawler)
    {
        $litSection = new LiturgySection();
        $litSection  = $this->getSection($crawler, "temporal");
        if ($crawler->filter('div.temporal button.accordion')->count() == 4)
        {
            $litSection = $this->addL2($crawler, "temporal", $litSection);
        }
        return $litSection;

    }

    protected function getSantoralText($crawler)
    {
        $litSection = new LiturgySection();
        if($crawler->filter('div.santoral')->count())
        {
            $litText  = $this->getSection($crawler, "santoral");
            if($crawler->filter('div.santoral button.accordion')->count() == 4)
            {
                $litText = $this->addL2($crawler, "santoral", $litText);
            }
            return $litText;
        }
        $litSection->setLoadStatus("Not_Found");
        return $litSection;
    }
    
    public function filter($data)
    {
        $litText = new LiturgyText();
        $crawler = new Crawler($data);
        if (!$this->isValidDate($crawler)){
            $litText->setLoadStatus("Not_Found");
            return $litText;
        }
        $litText->setLoadStatus("Success");
        $dayTitle = $crawler->filter('div.nav-dia center ')->first()->text();
        $dayTitle = str_replace(">>", "", $dayTitle);
        $dayTitle = str_replace("<<", "", $dayTitle);
        $dayTitle = trim($dayTitle);
        $dayTitle = preg_replace("/\s/"," ", $dayTitle);
        
        $litText->setDayTitle($dayTitle);
        $litText->setTemporalSection($this->getTemporalText($crawler));
        $litText->setSantoralSection($this->getSantoralText($crawler));
        return $litText;    
    }

    protected function makeReading($title, $subtitle, $intro, $text) :LiturgyReading
    {
        $reading = new LiturgyReading();
        $reading->setTitle($title);
        $reading->setSubtitle($subtitle);
        $reading->setIntroduction($intro);
        $reading->setText($text);
        return $reading;
    }
}

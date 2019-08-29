<?php

namespace App\Util;

use App\Entity\LiturgySection;
use App\Entity\PsalmReading;
use App\Factory\GospelReadingFactory;
use App\Factory\LiturgyReadingFactory;
use App\Factory\PsalmReadingFactory;
use Symfony\Component\DomCrawler\Crawler;

/**
 * \brief      Filters raw Data from the Igreja Santa Ines source.
 *
 *
 */
class IgrejaSantaInesFilter extends AbstractFilter
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

    protected function trimGospelText($gospelText)
    {
        $gospelText = str_replace(".Palavra da Salvaçào.", "", $gospelText);
        $gospelText = str_replace("Palavra da Salvaçào.", ".", $gospelText);
        $gospelText = str_replace(".Palavra da Salvação.", "", $gospelText);
        $gospelText = str_replace("Palavra da Salvação.", ".", $gospelText);
        $gospelText = str_replace(". .", ".", $gospelText);
        $gospelText = trim($gospelText);
        return $gospelText;
    }
    
    protected function getSection($crawler, $name)
    {
        $section = new LiturgySection();
        $section->setLoadStatus("Success");

        $l1Title = $crawler->filter('div.'.$name.' button.accordion')
                 ->first()->html();
        $l1Title = str_replace("<br>", " ", $l1Title);
        $l1Subtitle = $crawler->filter('div.'.$name.' div.panel div.cit_direita')
                    ->first()->text();
        $l1Intro = trim($crawler->filter('div.'.$name.' div.panel div.cit_direita_italico')->first()->text());
        $l1Crawler = $crawler->filter('div.'.$name.' div.panel')->first();
        $l1Text = $l1Crawler->filter('span')->each( function (Crawler $node, $i) {
                               return $node->text();
                           }
                           );
        
        $l1Text = implode("", $l1Text);
        $l1Text = str_replace("Palavra do Senhor.", '', $l1Text);
        $l1Text = trim($l1Text);
        $gospelTitle = $crawler->filter('div.'.$name.' button.accordion')->last()->html();
        $gospelTitle = str_replace("<br>", " ", $gospelTitle);

        $gospelSubtitle = $crawler->filter('div.'.$name.' div.panel div.cit_direita')->last()->text();
        $gospelIntro = trim($crawler->filter('div.'.$name.' div.panel')->last()->filter('div.cit_direita_italico')->first()->text());
        
        $gospelCrawler = $crawler->filter('div.'.$name.' div.panel')->last();
        $gospelText = $gospelCrawler->filter('span')->each( function (Crawler $node, $i) {
                               return $node->text();
                           }
                           );
        $gospelText = implode("", $gospelText);
        $gospelText = $this->trimGospelText($gospelText);
        $factory = new LiturgyReadingFactory();
        $firstReading = $factory->createReading(
            $l1Title,
            $l1Text,
            $l1Intro,
            $l1Subtitle
        );
        $factory = new GospelReadingFactory();
        $gospelReading = $factory->createReading(
            $gospelTitle,
            $gospelText,
            $gospelIntro,
            $gospelSubtitle
        );
        
        $section->setFirstReading($firstReading);
        $section->setPsalmReading($this->getPsalm($crawler, $name));
        $section->setGospelReading($gospelReading);
        $section = $this->addL2($crawler, $name, $section);
        return $section;
    }

    protected function getPsalm($crawler, $name): PsalmReading
    {
        $title = $crawler->filter('div.'.$name.' button.accordion')->eq(1)->html();
        $title = str_replace("<br>", " ", $title);
        $chorusCrawler = $crawler->filter('div.'.$name.' div.refrao_salmo span');
        $chorus = $chorusCrawler->each( function (Crawler $node, $i) {
                               return $node->text();
                           }
                           );
        $chorus = str_replace("Ou: Aleluia, Aleluia, Aleluia.", "", $chorus);
        $chorus = implode("\n", $chorus);
        $chorus = trim($chorus);
        $salmoCrawler = $crawler->filter('div.'.$name.' div.panel.salmo')->children('span, div.refrao_salmo2');

        $text = $salmoCrawler->each( function (Crawler $node, $i) {
                               return $node->text();
                           }
                           );
        $text = implode("", $text);
        $text = str_replace("R.", "\nR.\n", $text);
        $text = trim(str_replace(" \nR.\n ", "\nR.\n", $text));
        $factory = new psalmReadingFactory();
        return $factory->createReading($title, $text, $chorus);
    }

    protected function addL2($crawler, $name, $section)
    {
        if ($crawler->filter('div.'.$name.' button.accordion')->count() != 4) {
            return $section;
        }

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
        $l2Text = str_replace("Palavra do Senhor.", '', $l2Text);
        $l2Text = trim($l2Text);
        $factory = new LiturgyReadingFactory();

        $l2Reading = $factory->createReading(
            $l2Title,
            $l2Text,
            $l2Intro,
            $l2Subtitle
        );
        $section->setSecondReading($l2Reading);
        return $section;
    }
    
    protected function getTemporalText($crawler)
    {
        $litSection = new LiturgySection();
        $litSection = $this->getSection($crawler, "temporal");
        return $litSection;
    }

    protected function getSantoralText($crawler)
    {
        $litSection = new LiturgySection();
        if($crawler->filter('div.santoral')->count())
        {
            $litText  = $this->getSection($crawler, "santoral");
            return $litText;
        }
        $litSection->setLoadStatus("Not_Found");
        return $litSection;
    }
    
    protected function getDayTitle($crawler) : string
    {
        $dayTitle = $crawler->filter('div.nav-dia center ')->first()->text();
        $dayTitle = str_replace(">>", "", $dayTitle);
        $dayTitle = str_replace("<<", "", $dayTitle);
        $dayTitle = trim($dayTitle);
        $dayTitle = preg_replace("/\s/", " ", $dayTitle);
        return $dayTitle;
    }
}

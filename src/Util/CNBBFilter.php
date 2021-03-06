<?php

namespace App\Util;

use App\Entity\LiturgySection;
use App\Factory\GospelReadingFactory;
use App\Factory\PsalmReadingFactory;
use App\Factory\LiturgyReadingFactory;
use Symfony\Component\DomCrawler\Crawler;

/**
 * \brief      Filters raw data from the CNBBA source.
 *
 *
 */
class CNBBFilter extends AbstractFilter
{
    protected function isValidDate($crawler)
    {
        $checkFoundCrawler = $crawler->filter(
            'div.blog-post div#corpo_leituras'
        );
        if ($checkFoundCrawler->count()) {
            if ($checkFoundCrawler->first()->text("", true)
                === "Leitura não disponível.") {
                return false;
            }
        }
        return true;
    }

    protected function getReadingsIds($crawler, $position)
    {
        $subCrawler = $crawler->filter("div.sidebar-module > div.list-group")->eq($position);
        $refs= $subCrawler->filter("a.list-group-item")->each( function (Crawler $node, $i) {
            return $node->attr('href');
        }
        );
        $divIds = str_replace("javascript:showLeitura('", "", $refs);
        $divIds = str_replace("');", "", $divIds);
        return $divIds;
    }

    protected function extractText($subCrawler)
    {
        $text = $subCrawler->filter('div>span, span.tab_num, span.tab_num2, span.tabulacao')->each(
            function (Crawler $node, $i) {
                if(!($node->attr('class') === 'refrao_salmo')) {
                    return $node->text("void text", true);   
                }
            }
        );
        $text = implode("\n", $text);
        return $text;
    }
    
    protected function getReading($crawler, $divId)
    {
        $subCrawler = $crawler->filter("div#".$divId)->first();
        $title = $subCrawler->filter('h3.title-leitura')->text("Title not found", true);
        $intro = "";
        if ($subCrawler->filter('div.cit_direita_italico')->count())
        {
            $intro = $subCrawler->filter('div.cit_direita_italico')->text("intro not found", true);
            $intro = trim($intro);
        }
        $subtitle = $subCrawler->filter('div.cit_direita')->text("Subtitle not found", true);
        $text = $this->extractText($subCrawler);
        $text = str_replace("Palavra do Senhor.", '', $text);
        $factory = new LiturgyReadingFactory();
        return $factory->createReading($title, $text, $intro, $subtitle);
    }

    protected function trimChorus($chorus)
    {
        $chorus = str_replace("Ou: Aleluia, Aleluia, Aleluia.", "", $chorus);
        $chorus = implode("\n", $chorus);
        if (substr($chorus, 0, 3) === "R. ") {
            return substr($chorus,3);
        }elseif(substr($chorus, 0, 2) === "R.") {
            return substr($chorus,2);
        }
        return $chorus;
    }
    protected function getPsalm($crawler, $divId)
    {
        $subCrawler = $crawler->filter("div#".$divId)->first();
        $title = $subCrawler->filter('h3.title-leitura')->text("No Psalm Title found.", true);
        $chorus = $subCrawler->filter(
            'div.refrao_salmo,span.refrao_salmo'
        )->each(
            function (Crawler $node, $i) {
                return $node->text("", true);
            }
        );
        $chorus = $this->trimChorus($chorus);
        $text = $this->extractText($subCrawler);
        $text = str_replace("R.\nR.", "R.\n", $text);
        $text = str_replace("R. \nR.", "R.\n", $text);
        $factory = new PsalmReadingFactory();
        return $factory->createReading($title, $text, $chorus);
    }


    protected function getGospel($crawler, $divId)
    {
        $subCrawler = $crawler->filter("div#".$divId)->first();
        $title = $subCrawler->filter('h3.title-leitura')->text("No Gospel Title found", true);
        $introFilter = $subCrawler->filter('div.cit_direita_italico');
        $intro = "";
        if ($introFilter->count()) {
            $intro = $introFilter->text("Intro not found", true);
            $intro = preg_replace('/\s+/', ' ', $intro);
        }
        $subtitle = $subCrawler->filter('div.cit_direita')->text("No Gospel Subtitle found", true);
        $text = $this->extractText($subCrawler);
        $text = $this->trimGospelText($text);
        $factory = new GospelReadingFactory();
        return $factory->createReading($title, $text, $intro, $subtitle);
    }
    protected function trimGospelText($gospelText)
    {
        $gospelText = str_replace("Palavra da Salvaçào.", "", $gospelText);
        $gospelText = str_replace("Palavra da Salvação.", "", $gospelText);
        $gospelText = str_replace(". .", ".", $gospelText);
        $gospelText = trim($gospelText);
        return $gospelText;
    }

    protected function getTemporalText($crawler)
    {
        $ids = $this->getReadingsIds($crawler, 0);
        $readings = [];
        $readings[] = $this->getReading($crawler, $ids[0]);
        $readings[] = $this->getPsalm($crawler, $ids[1]);
        $litSection = new LiturgySection();
        $litSection->setLoadStatus("Success");
        $litSection->setFirstReading($readings[0]);
        $litSection->setPsalmReading($readings[1]);
        if (sizeof($ids) == 4) {
            $readings[] = $this->getReading($crawler, $ids[2]);
            $litSection->setSecondReading($readings[2]);
        }
        $readings[] = $this->getGospel($crawler, end($ids));
        $litSection->setGospelReading(end($readings));
        return $litSection;
    }
    
    protected function getSantoralText($crawler)
    {
        $litSection = new LiturgySection();
        $litSection->setLoadStatus("Not_Found");
        if (1<$crawler->filter("div.sidebar-module > div.list-group")->count()) {
            $litSection->setLoadStatus("Success");
            $extraIds = $this->getReadingsIds($crawler, 1);
            $readings = [];
            foreach ($extraIds as $divId){
                $readings[] = $this->getReading($crawler, $divId);
            }
            $litSection->setFirstReading($readings[0]);
            return $litSection;
        }
        return $litSection;
    }

    protected function getDayTitle($crawler) : string
    {
        $dayTitle = $crawler->filter('div.bs-callout h2 ')->first()->text("Day Title not found", true) ;
        $dayTitle = preg_replace('/\s+/', ' ', $dayTitle);
        return $dayTitle;
    }
}

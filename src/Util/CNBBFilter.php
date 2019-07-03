<?php

namespace App\Util;

use App\Entity\LiturgyText;
use App\Entity\LiturgySection;
use App\Entity\LiturgyReading;
use App\Entity\PsalmReading;
use Symfony\Component\DomCrawler\Crawler;

/**
 * \brief      Filters raw data from the CNBBA source.
 *
 *
 */
class CNBBFilter
{

    protected function isValidDate($crawler)
    {
        $checkFoundCrawler = $crawler->filter(
            'div.blog-post div#corpo_leituras'
        );
        if ($checkFoundCrawler->count())
        {
            if (trim($checkFoundCrawler->first()->text())
                === "Leitura não disponível.")
            {
                return false;
            }
        }
        return true;
    }
    protected function getTemporalText($crawler)
    {
        $litSection = new LiturgySection();
        $litSection->setLoadStatus("Success");
        $titlesCrawler = $crawler->filter('div#corpo_leituras div h3.title-leitura');
        $introCrawler = $crawler->filter('div#corpo_leituras div div div.cit_direita_italico');
        $subTitlesCrawler = $crawler->filter('div#corpo_leituras div div div.cit_direita');
        $firstReading = new LiturgyReading();
        $firstReading->setTitle(trim($titlesCrawler->first()->text()));
        $firstReading->setSubtitle(trim($subTitlesCrawler->first()->text()));
        $firstReading->setIntroduction(trim($introCrawler->first()->text()));
        $subCrawler = $crawler->filter('div#corpo_leituras div')->first();        
        $l1Text = $subCrawler->filter('div span')->each( function (Crawler $node, $i) {
                               return $node->text();
                           }
        );
        $l1Text = implode("\n", $l1Text);
        $firstReading->setText($l1Text);
        $litSection->setFirstReading($firstReading);
        
        $gospelTitle = trim($titlesCrawler->last()->text());

        $gospelSubtitle = trim($subTitlesCrawler->last()->text());

        $gospelIntro = trim($introCrawler->last()->text());
        $gospelIntro = preg_replace('/\s+/', ' ', $gospelIntro);
        
        $subCrawler = $crawler->filter('div#corpo_leituras div')->eq(2);
        $gospelCrawler = $subTitlesCrawler->last()->siblings();
        $gospelText = $gospelCrawler->filter('span')->each( function (Crawler $node, $i) {
                               return $node->text();
                           }
        );
        $gospelText = implode("\n", $gospelText);
        $gospelReading = new LiturgyReading();
        $gospelReading->setTitle($gospelTitle);
        $gospelReading->setSubtitle($gospelSubtitle);
        $gospelReading->setIntroduction($gospelIntro);
        $gospelReading->setText($gospelText);
        $litSection->setPsalmReading($this->getPsalm($crawler));
        $litSection->setSecondReading($this->getSecondReading($crawler));
        $litSection->setGospelReading($gospelReading);
        return $litSection;
    }

    protected function getSecondReading($crawler)
    {
        $checkCrawler = $crawler->filter('div.sidebar-module div.list-group')->first();
        if ($checkCrawler->filter("a.list-group-item")->count()< 4)
        {
            return null;
        }
        $titlesCrawler = $crawler->filter('div#corpo_leituras div h3.title-leitura');
        $introCrawler = $crawler->filter('div#corpo_leituras div div div.cit_direita_italico');
        $subTitlesCrawler = $crawler->filter('div#corpo_leituras div div div.cit_direita');
        $secondReading = new LiturgyReading();
        $secondReading->setTitle(trim($titlesCrawler->eq(2)->text()));
        $secondReading->setSubtitle(trim($subTitlesCrawler->eq(1)->text()));
        $secondReading->setIntroduction(trim($introCrawler->eq(1)->text()));
        $subCrawler = $crawler->filter('div#corpo_leituras')->children()->eq(2);
        $l2Text = $subCrawler->filter('span')->each( function (Crawler $node, $i) {
                               return $node->text();
                           }
        );
        $l2Text = implode("\n", $l2Text);
        $secondReading->setText($l2Text);
        return $secondReading;
    }
    
    protected function getPsalm($crawler)
    {
        $psalmReading = new PsalmReading();
        $titleCrawler = $crawler->filter('div .refrao_salmo')->first()->parents();
        $titleCrawler = $titleCrawler->filter('h3.title-leitura');
        $psalmReading->setTitle(trim($titleCrawler->first()->text()));
        $chorus = $crawler->filter('div .refrao_salmo')->each(function (Crawler $node, $i) {
                               return $node->text();
                           }
        );
        $chorus = implode("\n", $chorus);
        $psalmReading->setChorus($chorus);
        $salmoCrawler = $crawler->filter('div .refrao_salmo')->siblings();
        $salmoText = $salmoCrawler->filter('span')->each( function (Crawler $node, $i) {
                               return $node->text();
                           }
        );
        $salmoText = implode("\n", $salmoText);
        $salmoText = str_replace("\nR.\n", "\n\n", $salmoText);
        $salmoText = str_replace("R. \nR.", "R.", $salmoText);
        $psalmReading->setText($salmoText);
        return $psalmReading;
    }

    protected function getExtraReadingsIds($crawler)
    {
        $subCrawler = $crawler->filter("div.sidebar-module > div.list-group")->eq(1);
        $refs= $subCrawler->filter("a.list-group-item")->each( function (Crawler $node, $i) {
            return $node->attr('href');
        }
        );
        $divIds = str_replace("javascript:showLeitura('", "", $refs);
        $divIds = str_replace("');", "", $divIds);
        return $divIds;
    }
    protected function getReading($crawler , $divId)
    {
        $subCrawler = $crawler->filter("div#".$divId)->first();
        $title = trim($subCrawler->filter('h3.title-leitura')->text());
        $intro = $subCrawler->filter('div.cit_direita_italico')->text();
        $subTitle = $subCrawler->filter('div.cit_direita')->text();
        $reading = new LiturgyReading();
        $reading->setTitle($title);
        $reading->setSubtitle($subTitle);
        $reading->setIntroduction(trim($intro));
        $l1Text = $subCrawler->filter('p span')->each( function (Crawler $node, $i) {
                               return $node->text();
                           }
        );
        $l1Text = implode("\n", $l1Text);
        $reading->setText($l1Text);
        return $reading;
        
    }
    
    protected function getSantoralText($crawler)
    {
        $litSection = new LiturgySection();
        $litSection->setLoadStatus("Not_Found");
        if (1<$crawler->filter("div.sidebar-module > div.list-group")->count()) {
            $litSection->setLoadStatus("Success");
            $extraIds = $this->getExtraReadingsIds($crawler);
            $readings = [];
            foreach ($extraIds as $divId){
                $readings[] = $this->getReading($crawler, $divId);
            }
            $litSection->setFirstReading($readings[0]);
        }
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
        $dayTitle = trim($crawler->filter('div.bs-callout h2 ')->first()->text());
        $dayTitle = preg_replace('/\s+/', ' ', $dayTitle);
        $litText->setDayTitle($dayTitle);
        $litText->setTemporalSection($this->getTemporalText($crawler));
        $litText->setSantoralSection($this->getSantoralText($crawler));
        return $litText;
    }
}

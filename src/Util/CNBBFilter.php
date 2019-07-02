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
        $psalmReading = new PsalmReading();
        $psalmReading->setTitle(trim($titlesCrawler->eq(1)->text()));
        $psalmReading->setChorus($crawler->filter('div div.refrao_salmo')->first()->text());
        $salmoCrawler = $crawler->filter('div div.refrao_salmo')->siblings();
        $salmoText = $salmoCrawler->filter('span')->each( function (Crawler $node, $i) {
                               return $node->text();
                           }
        );
        $salmoText = implode("\n", $salmoText);
        $salmoText = str_replace("\nR.\n", "\n\n", $salmoText);
        $salmoText = str_replace("R. \nR.", "R.", $salmoText);
        $psalmReading->setText($salmoText);
        $litSection->setPsalmReading($psalmReading);
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
        $litSection->setGospelReading($gospelReading);
        return $litSection;
    }

    protected function getSantoralText($crawler)
    {
        $litSection = new LiturgySection();
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
        $dayTitle = trim($crawler->filter('div.bs-callout h2 ')->first()->text());
        $dayTitle = preg_replace('/\s+/', ' ', $dayTitle);
        $litText->setDayTitle($dayTitle);
        $litText->setTemporalSection($this->getTemporalText($crawler));
        $litText->setSantoralSection($this->getSantoralText($crawler));
        return $litText;
    }
}

<?php

namespace App\Util;

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
        $litText["status"] = "Success";
        $litText["hasL2"] = false;
        $titlesCrawler = $crawler->filter('div#corpo_leituras div h3.title-leitura');
        $introCrawler = $crawler->filter('div#corpo_leituras div div div.cit_direita_italico');
        $subTitlesCrawler = $crawler->filter('div#corpo_leituras div div div.cit_direita');
        $litText["l1Title"] = trim($titlesCrawler->first()->text());
        $litText["salmoTitle"] = trim($titlesCrawler->eq(1)->text());
        $litText["gospelTitle"] = trim($titlesCrawler->last()->text());
        $litText["l1Subtitle"] = trim($subTitlesCrawler->first()->text());
        $litText["gospelSubtitle"] = trim($subTitlesCrawler->last()->text());
        $litText["l1Intro"] = trim($introCrawler->first()->text());
        $litText["gospelIntro"] = trim($introCrawler->last()->text());
        $litText["gospelIntro"] = preg_replace('/\s+/', ' ', $litText["gospelIntro"]);
        


        $subCrawler = $crawler->filter('div#corpo_leituras div')->first();
        $litText["l1Text"] = $subCrawler->filter('div span')->each( function (Crawler $node, $i) {
                               return $node->text();
                           }
        );
        $litText["l1Text"] = implode("\n", $litText["l1Text"]);
        $litText["salmoChorus"] = $crawler->filter('div div.refrao_salmo')->first()->text();
        
        $salmoCrawler = $crawler->filter('div div.refrao_salmo')->siblings();
        $litText["salmoText"] = $salmoCrawler->filter('span')->each( function (Crawler $node, $i) {
                               return $node->text();
                           }
        );
        $litText["salmoText"] = implode("\n", $litText["salmoText"]);
        $litText["salmoText"] = str_replace("\nR.\n", "\n\n", $litText["salmoText"]);
        $litText["salmoText"] = str_replace("R. \nR.", "R.", $litText["salmoText"]);
        $subCrawler = $crawler->filter('div#corpo_leituras div')->eq(2);
        $gospelCrawler = $subTitlesCrawler->last()->siblings();
        $litText["gospelText"] = $gospelCrawler->filter('span')->each( function (Crawler $node, $i) {
                               return $node->text();
                           }
        );
        $litText["gospelText"] = implode("\n", $litText["gospelText"]);
        return $litText;

    }

    protected function getSantoralText($crawler)
    {
        return ["status" => "Not_Found"];
    }
    
    public function filter($data)
    {
        $litText = [];
        $crawler = new Crawler($data);
        if (!$this->isValidDate($crawler)){
            $litText["status"] = "Not_Found";
            return $litText;
        }
        $litText["status"] = "Success";
        $litText["dayTitle"] = trim($crawler->filter('div.bs-callout h2 ')->first()->text());
        $litText["dayTitle"] = preg_replace('/\s+/', ' ', $litText["dayTitle"]);

        $litText["temporal"] = $this->getTemporalText($crawler);
        $litText["santoral"] = $this->getSantoralText($crawler);
      
        return $litText;
    }
}

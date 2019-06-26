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

    public function filter($data)
    {
        $litText = [];
        $crawler = new Crawler($data);
        $litText["dayTitle"] = $crawler->filter('div.bs-callout h2 ')->first()->text();
        $litText["l1Title"] = $crawler->filter('h3.title-leitura')->first()->html();
        
        $litText["salmoTitle"] = $crawler->filter('h3.title-leitura')->eq(1)->html();
   
        $litText["gospelTitle"] = $crawler->filter('h3.title-leitura')->eq(2)->html();

        $subCrawler = $crawler->filter('div#corpo_leituras div')->first();
        $litText["l1Text"] = $subCrawler->filter('div')->text();
        $subCrawler = $crawler->filter('div#corpo_leituras div')->eq(1);
        $litText["salmoText"] = $subCrawler->filter('div')->eq(1)->text();
        $subCrawler = $crawler->filter('div#corpo_leituras div')->eq(2);
        $litText["gospelText"] = $subCrawler->filter('div')->text();
      
        return $litText;
    }
}

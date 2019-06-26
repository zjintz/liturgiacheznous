<?php

namespace App\Util;

use Symfony\Component\DomCrawler\Crawler;

/**
 * \brief      Filters raw Data from the Igreja Santa Ines source.
 *
 *
 */
class IgrejaSantaInesFilter
{

    public function filter($data)
    {
        $litText = [];
        $crawler = new Crawler($data);
        $litText["dayTitle"] = $crawler->filter('div.nav-dia center ')->first()->text();
        $litText["dayTitle"] = trim(str_replace(">>", "", $litText["dayTitle"]));
        $litText["l1Title"] = $crawler->filter('div.temporal button.accordion')->first()->html();
        $litText["l1Title"] = str_replace("<br>", " ", $litText["l1Title"]);
        
        $litText["salmoTitle"] = $crawler->filter('div.temporal button.accordion')->eq(1)->html();
        $litText["salmoTitle"] = str_replace("<br>", " ", $litText["salmoTitle"]);
        $litText["gospelTitle"] = $crawler->filter('div.temporal button.accordion')->eq(2)->html();
        $litText["gospelTitle"] = str_replace("<br>", " ", $litText["gospelTitle"]);
        $litText["l1Text"] = $crawler->filter('div.temporal div.panel')->first()->text();
        $litText["salmoText"] = $crawler->filter('div.temporal div.panel')->eq(1)->text();
        $litText["gospelText"] = $crawler->filter('div.temporal div.panel')->eq(2)->text();
      
        return $litText;
    }
}

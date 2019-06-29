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

    protected function isValidDate($crawler)
    {
        $checkFoundCrawler = $crawler->filter(
            'section.post-content div.czr-wp-the-content'
        );
        if( $checkFoundCrawler->count() )
        {
            if( trim($checkFoundCrawler->first()->text()) === "DIA INDEFINIDO" )
            {
                return false;
            }
        }
        return true;
    }

    protected function getSection($crawler, $name)
    {
        $section = [];
        $section["status"] = "Success";

        $section["l1Title"] = $crawler->filter('div.'.$name.' button.accordion')->first()->html();
        $section["l1Title"] = str_replace("<br>", " ", $section["l1Title"]);
        $section["l1Subtitle"] = $crawler->filter('div.'.$name.' div.panel div.cit_direita')->first()->text();
        $section["l1Intro"] = trim($crawler->filter('div.'.$name.' div.panel div.cit_direita_italico')->first()->text());
        $l1Crawler = $crawler->filter('div.'.$name.' div.panel')->first();
        $section["l1Text"] = $l1Crawler->filter('span')->each( function (Crawler $node, $i) {
                               return $node->text();
                           }
                           );
        
        $section["l1Text"] = implode("", $section["l1Text"]);

        $section["salmoTitle"] = $crawler->filter('div.'.$name.' button.accordion')->eq(1)->html();
        $section["salmoTitle"] = str_replace("<br>", " ", $section["salmoTitle"]);
        $section["gospelTitle"] = $crawler->filter('div.'.$name.' button.accordion')->last()->html();
        $section["gospelTitle"] = str_replace("<br>", " ", $section["gospelTitle"]);

        $section["salmoChorus"] = trim($crawler->filter('div.'.$name.' div.refrao_salmo span')->first()->text());
        $salmoCrawler = $crawler->filter('div.'.$name.' div.panel.salmo')->children('span, div.refrao_salmo2');

        $section["salmoText"] = $salmoCrawler->each( function (Crawler $node, $i) {
                               return $node->text();
                           }
                           );
        $section["salmoText"] = implode("", $section["salmoText"]);
        $section["salmoText"] = str_replace("R.", "\nR.\n", $section["salmoText"]);
        $section["salmoText"] = trim(str_replace(" \nR.\n ", "\nR.\n", $section["salmoText"]));

        $section["gospelSubtitle"] = $crawler->filter('div.'.$name.' div.panel div.cit_direita')->last()->text();
        $section["gospelIntro"] = trim($crawler->filter('div.'.$name.' div.panel')->last()->filter('div.cit_direita_italico')->first()->text());
        
        $gospelCrawler = $crawler->filter('div.'.$name.' div.panel')->last();
        $section["gospelText"] = $gospelCrawler->filter('span')->each( function (Crawler $node, $i) {
                               return $node->text();
                           }
                           );
        $section["gospelText"] = implode("", $section["gospelText"]);

      
        return $section;
    }

    protected function addL2($crawler, $name, $section)
    {

        $section["l2Title"] = $crawler->filter('div.'.$name.' button.accordion')->eq(2)->html();
        $section["l2Title"] = str_replace("<br>", " ", $section["l2Title"]);
        $section["l2Subtitle"] = $crawler->filter('div.'.$name.' div.panel div.cit_direita')->eq(1)->text();
        $section["l2Intro"] = trim($crawler->filter('div.'.$name.' div.panel div.cit_direita_italico')->eq(1)->text());
        $l2Crawler = $crawler->filter('div.'.$name.' div.panel')->eq(2);
        $section["l2Text"] = $l2Crawler->filter('span')->each( function (Crawler $node, $i) {
                               return $node->text();
                           }
                           );
        $section["l2Text"] = implode("", $section["l2Text"]);

        return $section;
    }
    
    protected function getTemporalText($crawler)
    {
        $litText  = $this->getSection($crawler, "temporal");
        $litText["hasL2"] = false;
        if ($crawler->filter('div.temporal button.accordion')->count() == 4)
        {
            $litText["hasL2"] = true;
            $litText = $this->addL2($crawler, "temporal", $litText);
        }
        return $litText;

    }

    protected function getSantoralText($crawler)
    {
        if($crawler->filter('div.santoral')->count())
        {
            $litText  = $this->getSection($crawler, "santoral");
            $litText["hasL2"] = false;
            if($crawler->filter('div.santoral button.accordion')->count() == 4)
            {
                $litText["hasL2"] = true;
                $litText = $this->addL2($crawler, "santoral", $litText);
            }
            return $litText;
        }
        
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
        $litText["dayTitle"] = $crawler->filter('div.nav-dia center ')->first()->text();
        $litText["dayTitle"] = str_replace(">>", "", $litText["dayTitle"]);
        $litText["dayTitle"] = str_replace("<<", "", $litText["dayTitle"]);
        $litText["dayTitle"] = trim($litText["dayTitle"]);
        $litText["dayTitle"] = preg_replace("/\s/"," ", $litText["dayTitle"]);
        $litText["temporal"] = $this->getTemporalText($crawler);
        $litText["santoral"] = $this->getSantoralText($crawler);
        return $litText;
    
    }
}

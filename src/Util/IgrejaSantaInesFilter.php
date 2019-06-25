<?php

namespace App\Util;

use Symfony\Component\DomCrawler\Crawler;

/**
 * \brief      Assembles documents from the CNBBA source.
 *
 *
 */
class IgrejaSantaInesFilter
{

    public function filter($data)
    {
        /*$crawler = new Crawler($data);
        $crawler = $crawler->filter('div.hoje')->first();
        $crawlerTemporal = $crawler->filter('div.panel')->first();
        $crawlerSantoral = $crawler->filter('div.santoral')->first();
        echo $crawlerTemporal->html();
        $wholeText = $crawlerTemporal->html().$crawlerSantoral->html();
        // instantiate and use the dompdf class
        return $crawlerTemporal->html()*/
        $pdfValido = 'hello workd';
        return $pdfValido;
    }

}

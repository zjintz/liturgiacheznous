<?php

namespace App\Controller;

use App\Form\LiturgyTextRequestType;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;




/**
 * \brief      TextAssembler Controller.
 *
 * \details    Provides mechanisms to assemble the text of each liturgy
 *             from different sources.
 *
 */
class TextAssemblerController extends AbstractController
{

    /**
     * @Route("/assembler", name="assembler_index")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(LiturgyTextRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array 
            $data = $form->getData();
            var_dump($data);
            return $this->redirectToRoute(
                'assembler_text',
                [
                    'text_format' => $data['text_format'],
                    'source' => $data['source'],
                    'liturgy_date' => $data['liturgy_date']->format('Y-m-d')
                ]
            );
        }
        
        return $this->render(
            'text_assembler/index.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/assembler/text/{text_format}/{source}/{liturgy_date}/",
     * name="assembler_text")
     */
    public function getText($text_format, $source, $liturgy_date)
    {
        $sourceRoute = $this->genSourceRoute($source, $liturgy_date);
        var_dump($sourceRoute);
        $rawContent = $this->getRawContent($sourceRoute);
        $crawler = new Crawler($rawContent);
        $crawler = $crawler->filter('div.hoje')->first();
        $crawlerTemporal = $crawler->filter('div.panel')->first();
        $crawlerSantoral = $crawler->filter('div.santoral')->first();
        echo $crawlerTemporal->html();
        $wholeText = $crawlerTemporal->html().$crawlerSantoral->html();
        // instantiate and use the dompdf class

        $dompdf = new Dompdf();
        $f;
        $l;
        if(headers_sent($f,$l))
        {
            echo $f,'<br/>',$l,'<br/>';
            die('now detect line');
        }
        $dompdf->loadHtml($crawlerTemporal->html());
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');
        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser
        $dompdf->stream();

    }

    private function genSourceRoute($source, $liturgy_date)
    {
        $miniDate= str_replace("-", "", $liturgy_date);
        $liturgyRoute = "http://www.igrejasantaines.com/liturgia/?h=".$miniDate;
        
        return $liturgyRoute;
    }

    private function getRawContent($url)
    {
        $link = curl_init();
        curl_setopt($link, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($link, CURLOPT_URL, $url);
        $data = curl_exec($link);
        curl_close($link);
        return $data;
    }

}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SummaryController extends AbstractController
{
    /**
     * @Route("/{_locale}/", name="app_summary",
     *     requirements={
     *         "_locale"="%app.locales%"
     *     }
     * )
     */
    public function index()
    {
        return $this->render('summary/index.html.twig', [
            'controller_name' => 'SummaryController',
        ]);
    }
}

<?php

namespace App\Controller;

use App\Util\LiturgyMailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * \brief      DemoMailer Controller.
 *
 * \details    Sends the demostration mail.
 *
 */
class DemoMailerController extends AbstractController
{
    /**
     * @Route("/demo/mail/{period}/{source}/{text_format}/",
     * name="demo_mail",
     * defaults={
     *         "period": "daily",
     *         "text_format": "ALL",
     *         "source": "ALL"
     *     },
     * requirements={
     *         "text_format": "daily|weekly|biweekly",
     *         "text_format": "DOCX|PDF|ALL",
     *         "source": "CNBB|Igreja_Santa_Ines|ALL"
     *     }
     *)
     */
    public function demoMail(
        $period,
        $source,
        $text_format,
        LiturgyMailer $liturgyMailer,
        Security $security

    ) {
        $user = $security->getUser();
        $email= $user->getEmail();
        $outcome = $liturgyMailer->deliverDemo(
            $period, $source, $text_format, $email
        );
        return $this->json(['outcome' => 'success']);
    }
}

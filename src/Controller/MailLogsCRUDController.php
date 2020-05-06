<?php

namespace App\Controller;

use Sonata\AdminBundle\Controller\CRUDController;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


/**
 * \brief      MailLogsCRUD Controller.
 *
 * \details    Show logs of the mailer.
 *
 */
class MailLogsCRUDController extends CRUDController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }
    
    public function listAction($id=NULL)
    {
        $logsDir = $this->params->get('kernel.logs_dir');
        $environment =$this->params->get('kernel.environment');
        $logs = file_get_contents($logsDir."/mailer.".$environment.".log");
        $logs = explode("\n", $logs);
        $logs = array_reverse($logs);
        return $this->renderWithExtraParams(
            'logs/mail_logs.html.twig',
            [
                'logs' => $logs,
            ]
        );
    }

}

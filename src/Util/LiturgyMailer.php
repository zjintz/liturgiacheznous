<?php

namespace App\Util;

use Twig\Environment;

/**
 * \brief     Mails the Liturgy texts..
 *o
 *
 */
class LiturgyMailer
{
    private $mailerAssistant;
    private $twig;
    private $mailer;
    
    public function __construct(
        MailerAssistant $mailerAssistant,
        Environment $twig,
        \Swift_Mailer $mailer
    ) {
        $this->assistant = $mailerAssistant;
        $this->twig = $twig;
        $this->mailer = $mailer;
    }
    
    public function sendTexts($textsDir, $subscriber)
    {
        $daysAhead = $subscriber->getEmailSubscription()->getDaysAhead();
        $daysCount = $this->countDays(
            $subscriber->getEmailSubscription()->getPeriodicity()
        );
        
        $dateAhead = new \DateTime();
        $dateAhead->add(
            new \DateInterval(
                'P'.$daysAhead.'D'
            )
        );
        $lastDateString = false;
        if ($daysCount > 1) {
            $lastDate = clone $dateAhead;
            $lastDate->add(
                new \DateInterval(
                    'P'.$daysCount.'D'
                )
            );
            $lastDateString = $lastDate->format('Y-m-d');
        }
        $dateString = $dateAhead->format('Y-m-d');
        $notFoundDocuments = [];
        $message = (new \Swift_Message('Textos Liturgicos'))
                 ->setFrom('no_reply@liturgiacheznous.org')
                 ->setTo($subscriber->getEmail());

        for ($i =0; $i< $daysCount; $i++) {
   
            $dateString = $dateAhead->format('Y-m-d');
            $documentsToAttach = [];
            $sources = $subscriber->getEmailSubscription()->getSource();
            $formats = $subscriber->getEmailSubscription()->getFormat();
            $invalidConfig = is_null($sources) || is_null($formats);
            if (!$invalidConfig) {
                foreach ($sources as $source) {
                    foreach ($formats as $format){
                        $documentsToAttach[] = $textsDir.'doc-'.$source.'_'.$dateString.'.'.$format;
                    }
                }
            }
            if (empty($documentsToAttach)){
                $message->setBody(
                    "Nenhum arquivo foi encontrado para sua assinatura. Talvez você tenha esquecido de escolher o formato ou fonte?. Você pode modificar em: liturgiacheznous.org"
                );
                $this->mailer->send($message);
                return;
            }

            $foundDocuments = [];
            foreach ($documentsToAttach as $path) {
                if (file_exists($path)) {
                    $foundDocuments[] = $path;
                    continue;
                }
                $notFoundDocuments[] = strstr($path, 'doc-');
            }

            foreach ($foundDocuments as $foundPath) {
                $message->attach(
                    \Swift_Attachment::fromPath(
                        $foundPath
                    )
                );
            }
            $dateAhead->add(
                new \DateInterval(
                    'P1D'
                )
            );

        }
        $message->setBody(
            $this->twig->render(
                // templates/emails/registration.html.twig
                'emails/daily_mail.html.twig',
                ['start_date' => $dateString,
                 'last_date'=> $lastDateString,
                 'not_found_list' => $notFoundDocuments,
                ]
            ),
            'text/html'
        );
        $this->mailer->send($message);
    }

    public function makeAllTexts($output, $period)
    {
        $daysCount = $this->countDays($period);
        $filesToMake = $this->assistant->listFilesToMake(
            $daysCount,
            new \DateTime()
        );
        foreach ($filesToMake as $toMake)
        {
            $output->writeln(
            '        - Making text Document:'.$toMake["file_name"]
            );
            $message = $this->assistant->makeLiturgyText($toMake, $output);
            $output->writeln(
                '        >'.$message
            );
        }
    }
    
    private function countDays($period)
    {
        if ($period === "daily") {
            return 1;
        }
        if ($period === "weekly") {
            return 7;
        }
            
        if ($period === "biweekly") {
            return 14;
        }
        if ($period === "1") {
            return 1;
        }
        if ($period === "7") {
            return 7;
        }
            
        if ($period === "14") {
            return 14;
        }
            
        return 0;
    }

}

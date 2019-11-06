<?php

namespace App\Util;

use Twig\Environment;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * \brief     Mails the Liturgy texts.
 *
 *
 */
class LiturgyMailer
{
    private $mailerAssistant;
    private $twig;
    private $mailer;
    private $textsDir;
    
    public function __construct(
        MailerAssistant $mailerAssistant,
        Environment $twig,
        \Swift_Mailer $mailer,
        ParameterBagInterface $parameterBag
    ) {
        $rootDir = $parameterBag->get('kernel.project_dir');
        $this->textsDir = $rootDir.'/data/liturgy_texts/';
        $this->mailerAssistant = $mailerAssistant;
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    /**
     * This function delivers the mails to every subscriber
     * acoording to their subscription.
     *
     * This function is used by the MailTexts command.
     *
     */
    public function deliverMailSubscriptions($period, $daysAhead, $output)
    {
        $this->mailerAssistant->logTextsDeliver($period);
        $enabledUsers = $this->mailerAssistant->getEnabledUsers();
         
        if (empty($enabledUsers)) {
            return 'There are no enabled users in the DB.';
        }
        $subscribedUsers = $this->mailerAssistant->getSubscribedUsers(
            $enabledUsers,
            $period,
            $daysAhead
        );
        if (empty($subscribedUsers)) {
            return 'There are no users with active email subscriptions for the given period: '.$period.' and the given \'days ahead\' option.';

        }
        $output->writeln('Making Liturgy texts ...');
        $this->makeAllTexts($output, $period);
        $output->writeln('Sending Liturgy texts ...');

        foreach ($subscribedUsers as $subscriber) {
            $output->writeln(
                '        - Sending to '.$subscriber->getEmail().' ('.$subscriber->getEmailSubscription()->getDaysAhead(). ' day ahead).'
            );
            $this->sendTexts($subscriber);
            
        }
        return 'Done.';
    }

    /**
     * This function delivers the mails to  a single subscriber that is 
     * requesting the demostration of the email attachments.
     *
     * This function is used by the demoMail route of the  DemoMailerController.
     */
    public function deliverDemo($period, $source, $textFormat, $email)
    {
        $filesToMake = $this->mailerAssistant->listDemoFiles(
            $period,
            $source,
            $textFormat
        );
        foreach ($filesToMake as $toMake)
        {
            $message = $this->mailerAssistant->makeLiturgyText($toMake, $this->textsDir);
        }
        $this->sendDemo($email, $filesToMake);
        return 'Done.';
    }

    private function sendDemo($email, $filesToSend)
    {
        $message = (new \Swift_Message('Textos Liturgicos'))
                 ->setFrom('no_reply@liturgiacheznous.org')
                 ->setTo($email);

        if (empty($filesToSend)){
            $message->setBody(
                "Nenhum arquivo foi encontrado para sua assinatura. Talvez você tenha esquecido de escolher o formato ou fonte?. Você pode modificar em: liturgiacheznous.org"
            );
            $this->mailer->send($message);
            return;
        }
        $foundDocuments = [];
        $notFoundDocuments = [];
        foreach ($filesToSend as $toSend) {
            if (file_exists($this->textsDir.$toSend['file_name'])) {
                $foundDocuments[] = $this->textsDir.$toSend['file_name'];
                continue;
            }
            $notFoundDocuments[] = strstr($toSend['file_name'], 'doc-');
        }
        foreach ($foundDocuments as $foundPath) {
            $message->attach(
                \Swift_Attachment::fromPath(
                    $foundPath
                )
            );
        }
        $message->setBody(
            $this->twig->render(
                // templates/emails/registration.html.twig
                'emails/daily_mail.html.twig',
                ['start_date' => $filesToSend[0]["date_string"],
                 'last_date'=> end($filesToSend)["date_string"],
                 'not_found_list' => $notFoundDocuments,
                ]
            ),
            'text/html'
        );
        $this->mailer->send($message);
    }

    
    private function sendTexts($subscriber)
    {
        //        $daysAhead = $subscriber->getEmailSubscription()->getDaysAhead();
        $daysAhead = 0;
        $daysCount = $this->mailerAssistant->countDays(
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
                        $documentsToAttach[] = $this->textsDir.'doc-'.$source.'_'.$dateString.'.'.$format;
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

    private function makeAllTexts($output, $period)
    {
        $daysCount = $this->mailerAssistant->countDays($period);
        $filesToMake = $this->mailerAssistant->listFilesToMake(
            $daysCount,
            new \DateTime()
        );
        foreach ($filesToMake as $toMake)
        {
            $output->writeln(
            '        - Making text Document:'.$toMake["file_name"]
            );
            $message = $this->mailerAssistant->makeLiturgyText($toMake, $this->textsDir);
            $output->writeln(
                '        >'.$message
            );
        }
    }
    
}

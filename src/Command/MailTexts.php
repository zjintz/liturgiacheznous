<?php

// src/Command/CreateUserCommand.php
namespace App\Command;

use App\Application\Sonata\UserBundle\Entity\User;
use App\Util\CNBBAssembler;
use App\Util\IgrejaSantaInesAssembler;
use App\Util\MailerAssistant;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Environment;

/**
 * Command to send liturgy texts.
 *
 *
 */
class MailTexts extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:mail-texts';
    private $entityManager;
    private $cnbbAssembler;
    private $santaInesAssembler;
    private $parameterBag;
    private $mailer;
    private $twig;
    private $assistant;
    
    public function __construct(
        IgrejaSantaInesAssembler $santaInesAssembler,
        CNBBAssembler $cnbbAssembler,
        ParameterBagInterface $parameterBag,
        \Swift_Mailer $mailer,
        Environment $twig,
        MailerAssistant $mailerAssistant
    ) {
        $this->cnbbAssembler = $cnbbAssembler;
        $this->santaInesAssembler = $santaInesAssembler;
        $this->parameterBag= $parameterBag;
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->assistant = $mailerAssistant;
        parent::__construct();
    }
        
    
    protected function configure()
    {
        
        $this
             ->setDescription('Sends the Liturgy texts..')
             ->setHelp(
                 'Search in the DB for active users, and according to their email subscription send the liturgy text.'
             )
            ->addOption(
                'period',
                null,
                InputOption::VALUE_REQUIRED,
                'Period of the subscription? daily, weekly, biweekly.',
                'daily'
            )
            ->addOption(
                'days-ahead',
                null,
                InputOption::VALUE_REQUIRED,
                'It will only mail the texts to the subscribers that have configured their subscription \'Days ahead\' to one of thes values : 1 , 2 or 3. Any other value means it will include subscribers ignored this filter.',
                0
            );
 
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $period = $input->getOption("period");
        $daysAhead = $input->getOption("days-ahead");
        $output->writeln([
            'Mail Texts',
            '============',
            "Period: ".$period,
            '',
        ]);
        $enabledUsers = $this->assistant->getEnabledUsers();
         
    // the value returned by someMethod() can be an iterator (https://secure.php.net/iterator)
    // that generates and returns the messages with the 'yield' PHP keyword
         //$output->writeln($this->someMethod());


        if (empty($enabledUsers)) {
            $output->writeln('There are no enabled users in the DB.');
            return;
        }
        
        $subscribedUsers = $this->assistant->getSubscribedUsers(
            $enabledUsers,
            $period,
            $daysAhead
        );
        
        if (empty($subscribedUsers)) {
            $output->writeln(
                'There are no users with active email subscriptions for the given period: '.$period.' and the given \'days ahead\' option.'
            );
            return;
        }
        
        $this->makeAllTexts($output, $period);
        $output->writeln('Sending Liturgy texts ...');
        $rootDir = $this->parameterBag->get('kernel.project_dir');
        $textsDir = $rootDir.'/data/liturgy_texts/';
        foreach ($subscribedUsers as $subscriber) {
            $output->writeln('        - Sending to '.$subscriber->getEmail().' ('.$subscriber->getEmailSubscription()->getDaysAhead(). ' day ahead).' );
            $this->sendTexts($textsDir, $subscriber);
            
        }
        $output->write('Done.');
    }
    
    private function makeAllTexts($output, $period)
    {
        $daysCount = $this->countDays($period);
        
        $output->writeln('Making Liturgy texts ...');
        $filesToMake = $this->assistant->listFilesToMake(
            $daysCount,
            new \DateTime()
        );
        foreach ($filesToMake as $toMake)
        {
            $this->makeLiturgyText($toMake, $output);
        }
    }


    private function makeLiturgyText($toMake, $output)
    {
        $rootDir = $this->parameterBag->get('kernel.project_dir');
        $dateString = $toMake["date_string"];
        $filePath = $rootDir.'/data/liturgy_texts/'.$toMake["file_name"];
        $output->writeln(
            '        - Making text Document:'.$filePath
         );
        if(file_exists($filePath)){
            $output->writeln(
                '        >>> '.$toMake["file_name"].' is already there.'
            );
            return;
        }
        $assembler = $this->getAssembler($toMake["source"]);
        $docFile = $assembler->getDocument($dateString, $toMake["format"]);
        rename($docFile, $filePath);
    }

    private function getAssembler($source)
    {
        if($source === "CNBB")
            return $this->cnbbAssembler;
        return $this->santaInesAssembler;
    }
    
    private function  sendTexts($textsDir, $subscriber)
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

        $message = (new \Swift_Message('Textos Liturgicos'))
                 ->setFrom('no_reply@liturgiacheznous.org')
                 ->setTo($subscriber->getEmail())
                 ->setBody(
                     $this->twig->render(
                         // templates/emails/registration.html.twig
                         'emails/daily_mail.html.twig',
                         ['start_date' => $dateString,
                          'last_date'=> $lastDateString,
                         ]
                     ),
                     'text/html'
                 );
        for ($i =0; $i< $daysCount; $i++) {
   
            $dateString = $dateAhead->format('Y-m-d');
            $message
                ->attach(
                    \Swift_Attachment::fromPath(
                        $textsDir.'doc-CNBB_'.$dateString.".DOCX"
                    )
                )
                ->attach(
                    \Swift_Attachment::fromPath(
                        $textsDir.'doc-CNBB_'.$dateString.".PDF"
                    )
                )
                ->attach(
                    \Swift_Attachment::fromPath(
                        $textsDir.'doc-Igreja_Santa_Ines_'.$dateString.".DOCX"
                    )
                )
                ->attach(
                    \Swift_Attachment::fromPath(
                        $textsDir.'doc-Igreja_Santa_Ines_'.$dateString.".PDF"
                    )
                );
            $dateAhead->add(
                new \DateInterval(
                    'P1D'
                )
            );

        }
        $this->mailer->send($message);
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

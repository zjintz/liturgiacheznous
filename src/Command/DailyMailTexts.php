<?php

// src/Command/CreateUserCommand.php
namespace App\Command;

use App\Application\Sonata\UserBundle\Entity\User;
use App\Util\CNBBAssembler;
use App\Util\IgrejaSantaInesAssembler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Command to send liturgy texts daily.
 *
 *
 */
class DailyMailTexts extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:daily-mail-texts';
    private $entityManager;
    private $cnbbAssembler;
    private $santaInesAssembler;
    private $parameterBag;
    private $mailer;
    
    public function __construct(
        EntityManagerInterface $entityManager,
        IgrejaSantaInesAssembler $santaInesAssembler,
        CNBBAssembler $cnbbAssembler,
        ParameterBagInterface $parameterBag,
        \Swift_Mailer $mailer
    ) {
        $this->entityManager = $entityManager;
        $this->cnbbAssembler = $cnbbAssembler;
        $this->santaInesAssembler = $santaInesAssembler;
        $this->parameterBag= $parameterBag;
        $this->mailer = $mailer;
        parent::__construct();
    }
        
    
    protected function configure()
    {
        
        $this
             ->setDescription('Sends the daily Liturgy texts..')
             ->setHelp(
                 'Search in the DB for active users, and acoording to their email subscription send the liturgy text.'
             );
 
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
         $output->writeln([
             'Daily Mail Texts',
             '============',
             '',
         ]);
         $userRepo = $this->entityManager->getRepository(User::class);
         $enabledUsers = $userRepo->findBy(
             ['enabled'=>true]
         );
         
    // the value returned by someMethod() can be an iterator (https://secure.php.net/iterator)
    // that generates and returns the messages with the 'yield' PHP keyword
         //$output->writeln($this->someMethod());


         if (empty($enabledUsers)) {
             $output->writeln('There are no enabled users in the DB.');
             return;
         }
         
         $subscribedUsers = $this->getSubscribedUsers($enabledUsers);

         if (empty($subscribedUsers)) {
             $output->writeln('There are no users with active email subscriptions.');
             return;
         }

         // outputs a message without adding a "\n" at the end of the line
         $output->writeln('Making Liturgy texts ...');
         $this->makeLiturgyTexts(1, $output, "CNBB", $this->cnbbAssembler);
         $this->makeLiturgyTexts(1, $output, "Igreja_Santa_Ines", $this->santaInesAssembler);
         $this->makeLiturgyTexts(2, $output, 'CNBB', $this->cnbbAssembler);
         $this->makeLiturgyTexts(2, $output, 'Igreja_Santa_Ines', $this->santaInesAssembler);
         $this->makeLiturgyTexts(3, $output, 'CNBB', $this->cnbbAssembler);
         $this->makeLiturgyTexts(3, $output, 'Igreja_Santa_Ines', $this->santaInesAssembler);
         $output->writeln('Sending Liturgy texts ...');
         $rootDir = $this->parameterBag->get('kernel.project_dir');
         $textsDir = $rootDir.'/data/liturgy_texts/';
         foreach ($subscribedUsers as $subscriber) {
             $output->writeln('        - Sending to '.$subscriber->getEmail().' ('.$subscriber->getEmailSubscription()->getDaysAhead(). ' day ahead).' );
             $this->sendTexts($textsDir, $subscriber);
        
         }
         $output->write('Done.');
    }
    private function  sendTexts($textsDir, $subscriber)
    {
        $dateAhead = new \DateTime();
        $dateAhead->add(
            new \DateInterval(
                'P'.$subscriber->getEmailSubscription()->getDaysAhead().'D'
            )
        );
        $dateString = $dateAhead->format('Y-m-d');
        $message = (new \Swift_Message('Textos Liturgicos'))
                 ->setFrom('no_reply@liturgiacheznous.org')
                 ->setTo($subscriber->getEmail())
                 ->setBody(
                     $this->renderView(
                         // templates/emails/registration.html.twig
                         'emails/account_enabled.html.twig',
                         ['name' => $user->getName()]
                     ),
                     'text/html'
                 )
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
            $this->mailer->send($message);
    }

    private function makeLiturgyTexts($daysAhead, $output, $source, $assembler)
    {
        $rootDir = $this->parameterBag->get('kernel.project_dir');
        $dateAhead = new \DateTime();
        $dateAhead->add(new \DateInterval('P'.$daysAhead.'D'));
        $dateString = $dateAhead->format('Y-m-d');
        $output->writeln(
            '        - '.$daysAhead.' Day ahead, DOCX file from '.$source.', date: '.$dateString
         );
         $docFile = $assembler->getDocument($dateString, "DOCX");
         $destiny = $rootDir.'/data/liturgy_texts/doc-'.$source.'_'.$dateString.".DOCX";
         rename($docFile, $destiny);
         $output->writeln(
             '        Text: '.$destiny
         );
         $output->writeln(
             '        - '.$daysAhead.' Day ahead, PDF file from '.$source.', date: '.$dateString
         );
         $docFile = $assembler->getDocument($dateString, "PDF");
         $destiny = $rootDir.'/data/liturgy_texts/doc-'.$source.'_'.$dateString.".PDF";
         rename($docFile, $destiny);
         $output->writeln(
             '        - Text: '.$destiny
         );
    }

    private function getSubscribedUsers($enabledUsers)
    {
        $subscribedUsers = [];
        foreach($enabledUsers as $user){
            $subsc = $user->getEmailSubscription();
            if (!is_null($subsc)) {
                if ($subsc->getIsActive()) {
                    $subscribedUsers[] = $user;
                }
            }
        }
        return $subscribedUsers;
    }
}

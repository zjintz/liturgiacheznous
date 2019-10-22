<?php

namespace App\Command;

use App\Application\Sonata\UserBundle\Entity\User;
use App\Util\MailerAssistant;
use App\Util\LiturgyMailer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Command to send liturgy texts.
 *
 *
 */
class MailTexts extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:mail-texts';

    private $parameterBag;
    private $assistant;
    private $liturgyMailer;
    
    public function __construct(
        ParameterBagInterface $parameterBag,
        MailerAssistant $mailerAssistant,
        LiturgyMailer $liturgyMailer
    ) {
        $this->parameterBag= $parameterBag;
        $this->assistant = $mailerAssistant;
        $this->liturgyMailer = $liturgyMailer;
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
                1
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
        $output->writeln('Making Liturgy texts ...');
        $this->liturgyMailer->makeAllTexts($output, $period);
        $output->writeln('Sending Liturgy texts ...');
        $rootDir = $this->parameterBag->get('kernel.project_dir');
        $textsDir = $rootDir.'/data/liturgy_texts/';
        foreach ($subscribedUsers as $subscriber) {
            $output->writeln(
                '        - Sending to '.$subscriber->getEmail().' ('.$subscriber->getEmailSubscription()->getDaysAhead(). ' day ahead).'
            );
            $this->liturgyMailer->sendTexts($textsDir, $subscriber);
            
        }
        $output->write('Done.');
    }
}

<?php

namespace App\Command;

use App\Application\Sonata\UserBundle\Entity\User;
use App\Util\LiturgyMailer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;


/**
 * Command to send liturgy texts.
 *
 *
 */
class MailTexts extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:mail-texts';

    private $liturgyMailer;
    
    public function __construct(
        LiturgyMailer $liturgyMailer
    ) {
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
        $result = $this->liturgyMailer->deliverMailSubscriptions(
            $period,
            $daysAhead,
            $output
        );
        $output->writeln($result);
    }
}

<?php

namespace App\Tests\Command;

use App\Repository\UserRepository;
use App\DataFixtures\AppFixtures;
use App\DataFixtures\UserTestFixtures;
use App\DataFixtures\UserTestActiveSubsFixtures;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;


/**
 * Test the command that sends the daily liturgy texts.
 *
 */
class DailyMailTextsTest extends WebTestCase
{
    use FixturesTrait;
    
    public function testExecuteNoUsers()
    {
        $this->loadFixtures([]);
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:daily-mail-texts');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('There are no enabled users in the DB.', $output);

        // ...
    }

    public function testExecuteNoActiveUsers()
    {
        $this->loadFixtures([UserTestFixtures::class]);
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:daily-mail-texts');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('There are no users with active email subscriptions.', $output);

        // ...
    }

    public function testExecute()
    {
        $this->loadFixtures([UserTestActiveSubsFixtures::class, AppFixtures::class]);
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:daily-mail-texts');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('Making Liturgy texts ...', $output);
        $this->assertContains('        - 1 Day ahead, DOCX file from CNBB', $output);
        $this->assertContains('        - 1 Day ahead, PDF file from CNBB', $output);
        $this->assertContains('        - 1 Day ahead, DOCX file from Igreja_Santa_Ines', $output);
        $this->assertContains('        - 1 Day ahead, PDF file from Igreja_Santa_Ines', $output);
        $this->assertContains('        - 2 Day ahead, DOCX file from CNBB', $output);
        $this->assertContains('        - 2 Day ahead, PDF file from CNBB', $output);
        $this->assertContains('        - 2 Day ahead, DOCX file from Igreja_Santa_Ines', $output);
        $this->assertContains('        - 2 Day ahead, PDF file from Igreja_Santa_Ines', $output);
        $this->assertContains('        - 3 Day ahead, DOCX file from CNBB', $output);
        $this->assertContains('        - 3 Day ahead, PDF file from CNBB', $output);
        $this->assertContains('        - 3 Day ahead, DOCX file from Igreja_Santa_Ines', $output);
        $this->assertContains('        - 3 Day ahead, PDF file from Igreja_Santa_Ines', $output);

        $this->assertContains('Sending Liturgy texts ...', $output);
        $this->assertContains('        - Sending to editor@test.com (1 day ahead).', $output);
        $this->assertContains('Done.', $output);
        // ...
    }
}


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
 * Test the command that sends the liturgy texts.
 *
 */
class MailTextsTest extends WebTestCase
{
    use FixturesTrait;
    
    public function testExecuteNoUsers()
    {
        $this->loadFixtures([]);
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:mail-texts');
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

        $command = $application->find('app:mail-texts');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('There are no users with active email subscriptions.', $output);
    }

    public function testExecute()
    {
        $this->loadFixtures([UserTestActiveSubsFixtures::class, AppFixtures::class]);
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('app:mail-texts');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('Making Liturgy texts ...', $output);
        $this->assertContains('        - Making text Document:', $output);
        $this->assertContains('Sending Liturgy texts ...', $output);
        $this->assertContains('        - Sending to editor@test.com (1 day ahead).', $output);
        $this->assertContains('Done.', $output);
    }
}


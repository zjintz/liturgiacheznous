<?php
namespace App\Tests\Util;

use App\Application\Sonata\UserBundle\Entity\User;
use App\Entity\EmailSubscription;
use App\Util\MailerAssistant;
use App\Util\CNBBAssembler;
use App\Util\IgrejaSantaInesAssembler;
use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MailerAssistantTest extends TestCase
{
    protected function mockEntityManagerVoid()
    {
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->expects($this->any())
            ->method('findBy')
            ->willReturn([]);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);
        return $entityManager;
    }


    protected function mockUser(
        bool $isEnabled,
        bool $isSubscribed,
        string $period = "1",
        int $daysAhead = 1
    ) {
        $subscription = $this->createMock(EmailSubscription::class);
        $subscription->expects($this->any())
            ->method('getIsActive')
            ->willReturn($isSubscribed);
        $subscription->expects($this->any())
            ->method('getPeriodicity')
            ->willReturn($period);
        $subscription->expects($this->any())
            ->method('getDaysAhead')
            ->willReturn($daysAhead);
        
        $user = $this->createMock(User::class);
        $user->expects($this->any())
            ->method('isEnabled')
            ->willReturn($isEnabled);
        $user->expects($this->any())
            ->method('getEmailSubscription')
            ->willReturn($subscription);
        return $user;
    }
    
    protected function mockEntityManager($enabledCount)
    {
        $enabledUsers = [];
        for ($i=0; $i<$enabledCount; $i++) {
            $user = $this->mockUser(true, false);
            $enabledUsers[] = $user;
        }
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->expects($this->any())
            ->method('findBy')
            ->willReturn($enabledUsers);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);
        return $entityManager;
    }

    protected function makeMailerAssitant($enabledCount)
    {
        $parameterBag = $this->createMock(ParameterBagInterface::class);
        $cnbb = $this->createMock(CNBBAssembler::class);
        $santaInes = $this->createMock(IgrejaSantaInesAssembler::class);
        $assistant = new MailerAssistant(
            $this->mockEntityManager($enabledCount),
            $parameterBag,
            $cnbb,
            $santaInes
        );
        return $assistant;
    }
    
    public function testGetEnabledUsersVoid()
    {
        $assistant =  $this->makeMailerAssitant(0);
        $this->assertEquals([], $assistant->getEnabledUsers());
    }

    public function testGetEnabledUsers()
    {
        $assistant = $this->makeMailerAssitant(0);
        $this->assertEquals([], $assistant->getEnabledUsers());
        $assistant = $this->makeMailerAssitant(2);
        $this->assertEquals(2, count($assistant->getEnabledUsers()));
    }

    public function testGetSubscribedUsers()
    {
        $assistant = $this->makeMailerAssitant(0);
        $this->assertEquals([], $assistant->getSubscribedUsers([]));
        $assistant = $this->makeMailerAssitant(0);
        $expectedUsers = $assistant->getSubscribedUsers(
            [$this->mockUser(true, true)]
        );
                       
        $this->assertTrue(
            $expectedUsers[0]->getEmailSubscription()->getIsActive()
        );
    }

    public function testGetSubscribedUsersWeekly()
    {
        $assistant = $this->makeMailerAssitant(0);
        $expectedUsers = $assistant->getSubscribedUsers(
            [$this->mockUser(true, true)],
            "weekly"
        );
        $this->assertEquals([], $expectedUsers);

        $expectedUsers = $assistant->getSubscribedUsers(
            [$this->mockUser(true, true, "7")],
            "weekly"
        );
        $this->assertTrue(
            $expectedUsers[0]->getEmailSubscription()->getIsActive()
        );
        $this->assertEquals(
            "7",
            $expectedUsers[0]->getEmailSubscription()->getPeriodicity()
        );
    }

    /**
     * Tests the GetSubsbriedUsers function with biweekly subscription.
     *
     *
     */
    public function testGetSubscribedUsersBiWeekly()
    {
        $assistant = $this->makeMailerAssitant(0);
        $expectedUsers = $assistant->getSubscribedUsers(
            [$this->mockUser(true, true)],
            "biweekly"
        );
        $this->assertEquals([], $expectedUsers);

        $expectedUsers = $assistant->getSubscribedUsers(
            [$this->mockUser(true, true, "14", 3)],
            "biweekly",
            1
        );
        $this->assertEmpty($expectedUsers);
        $expectedUsers = $assistant->getSubscribedUsers(
            [$this->mockUser(true, true, "14", 3)],
            "biweekly",
            3
        );
        $this->assertEquals(
            "14",
            $expectedUsers[0]->getEmailSubscription()->getPeriodicity()
        );
        $this->assertEquals(
            3,
            $expectedUsers[0]->getEmailSubscription()->getDaysAhead()
        );
    }

    public function testListFilesToMake()
    {
        $assistant = $this->makeMailerAssitant(0);
        $dateTest = new \DateTime("2010-01-01");
        $filesToMake = $assistant->listFilesToMake(1, $dateTest);
        $this->assertEquals(12, count($filesToMake));
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_2010-01-02.DOCX',
             "date_string"=> "2010-01-02",
             "source" => "CNBB",
             "format" => "DOCX",
            ],
            $filesToMake[0]
        );
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_2010-01-02.PDF',
             "date_string"=> "2010-01-02",
             "source" => "CNBB",
             "format" => "PDF",
            ],
            $filesToMake[1]
        );
        
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_2010-01-02.DOCX',
             "date_string"=> "2010-01-02",
             "source" => "Igreja_Santa_Ines",
             "format" => "DOCX",
            ],
            $filesToMake[2]
        );
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_2010-01-02.PDF',
             "date_string"=> "2010-01-02",
             "source" => "Igreja_Santa_Ines",
             "format" => "PDF",
            ],
            $filesToMake[3]
        );
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_2010-01-04.PDF',
             "date_string"=> "2010-01-04",
             "source" => "Igreja_Santa_Ines",
             "format" => "PDF",
            ],
            $filesToMake[11]
        );
    }

    public function testListFilesToMakeWeek()
    {
        $assistant = $this->makeMailerAssitant(0);
        $dateTest = new \DateTime("2010-01-01");
        $filesToMake = $assistant->listFilesToMake(7, $dateTest);
        $this->assertEquals(36, count($filesToMake));
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_2010-01-02.DOCX',
             "date_string"=> "2010-01-02",
             "source" => "CNBB",
             "format" => "DOCX",
            ],
            $filesToMake[0]
        );
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_2010-01-02.PDF',
             "date_string"=> "2010-01-02",
             "source" => "CNBB",
             "format" => "PDF",
            ],
            $filesToMake[1]
        );
        
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_2010-01-02.DOCX',
             "date_string"=> "2010-01-02",
             "source" => "Igreja_Santa_Ines",
             "format" => "DOCX",
            ],
            $filesToMake[2]
        );
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_2010-01-02.PDF',
             "date_string"=> "2010-01-02",
             "source" => "Igreja_Santa_Ines",
             "format" => "PDF",
            ],
            $filesToMake[3]
        );
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_2010-01-04.PDF',
             "date_string"=> "2010-01-04",
             "source" => "Igreja_Santa_Ines",
             "format" => "PDF",
            ],
            $filesToMake[11]
        );
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_2010-01-10.PDF',
             "date_string"=> "2010-01-10",
             "source" => "Igreja_Santa_Ines",
             "format" => "PDF",
            ],
            $filesToMake[35]
        );
    }

    public function testListFilesToMakeBiWeek()
    {
        $assistant = $this->makeMailerAssitant(0);
        $dateTest = new \DateTime("2010-01-01");
        $filesToMake = $assistant->listFilesToMake(14, $dateTest);
        $this->assertEquals(64, count($filesToMake));
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_2010-01-02.DOCX',
             "date_string"=> "2010-01-02",
             "source" => "CNBB",
             "format" => "DOCX",
            ],
            $filesToMake[0]
        );
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_2010-01-02.PDF',
             "date_string"=> "2010-01-02",
             "source" => "CNBB",
             "format" => "PDF",
            ],
            $filesToMake[1]
        );
        
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_2010-01-02.DOCX',
             "date_string"=> "2010-01-02",
             "source" => "Igreja_Santa_Ines",
             "format" => "DOCX",
            ],
            $filesToMake[2]
        );
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_2010-01-02.PDF',
             "date_string"=> "2010-01-02",
             "source" => "Igreja_Santa_Ines",
             "format" => "PDF",
            ],
            $filesToMake[3]
        );
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_2010-01-04.PDF',
             "date_string"=> "2010-01-04",
             "source" => "Igreja_Santa_Ines",
             "format" => "PDF",
            ],
            $filesToMake[11]
        );
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_2010-01-10.PDF',
             "date_string"=> "2010-01-10",
             "source" => "Igreja_Santa_Ines",
             "format" => "PDF",
            ],
            $filesToMake[35]
        );

        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_2010-01-17.PDF',
             "date_string"=> "2010-01-17",
             "source" => "Igreja_Santa_Ines",
             "format" => "PDF",
            ],
            $filesToMake[63]
        );
    }
}

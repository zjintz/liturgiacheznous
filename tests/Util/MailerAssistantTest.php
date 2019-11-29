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
        $cnbb = $this->createMock(CNBBAssembler::class);
        $santaInes = $this->createMock(IgrejaSantaInesAssembler::class);
        $assistant = new MailerAssistant(
            $this->mockEntityManager($enabledCount),
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

    protected function assertFirstDayFiles($filesToMake)
    {
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_2010-01-01.DOCX',
             "date_string"=> "2010-01-01",
             "source" => "CNBB",
             "format" => "DOCX",
            ],
            $filesToMake[0]
        );
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_2010-01-01.PDF',
             "date_string"=> "2010-01-01",
             "source" => "CNBB",
             "format" => "PDF",
            ],
            $filesToMake[1]
        );
        
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_2010-01-01.DOCX',
             "date_string"=> "2010-01-01",
             "source" => "Igreja_Santa_Ines",
             "format" => "DOCX",
            ],
            $filesToMake[2]
        );
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_2010-01-01.PDF',
             "date_string"=> "2010-01-01",
             "source" => "Igreja_Santa_Ines",
             "format" => "PDF",
            ],
            $filesToMake[3]
        );
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_2010-01-03.PDF',
             "date_string"=> "2010-01-03",
             "source" => "Igreja_Santa_Ines",
             "format" => "PDF",
            ],
            $filesToMake[11]
        );
        
    }

    public function testListFilesToMake()
    {
        $assistant = $this->makeMailerAssitant(0);
        $dateTest = new \DateTime("2010-01-01");
        $filesToMake = $assistant->listFilesToMake(1, $dateTest);
        $this->assertEquals(12, count($filesToMake));
        $this->assertFirstDayFiles($filesToMake);
    }

    public function testListFilesToMakeWeek()
    {
        $assistant = $this->makeMailerAssitant(0);
        $dateTest = new \DateTime("2010-01-01");
        $filesToMake = $assistant->listFilesToMake(7, $dateTest);
        $this->assertEquals(36, count($filesToMake));
        $this->assertFirstDayFiles($filesToMake);
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_2010-01-09.PDF',
             "date_string"=> "2010-01-09",
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
        $this->assertFirstDayFiles($filesToMake);
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_2010-01-03.PDF',
             "date_string"=> "2010-01-03",
             "source" => "Igreja_Santa_Ines",
             "format" => "PDF",
            ],
            $filesToMake[11]
        );
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_2010-01-09.PDF',
             "date_string"=> "2010-01-09",
             "source" => "Igreja_Santa_Ines",
             "format" => "PDF",
            ],
            $filesToMake[35]
        );

        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_2010-01-16.PDF',
             "date_string"=> "2010-01-16",
             "source" => "Igreja_Santa_Ines",
             "format" => "PDF",
            ],
            $filesToMake[63]
        );
    }

    private function getTomorrowDateString()
    {
        $newDate = new \DateTime();
        $newDate->add(
            new \DateInterval(
                'P1D'
            )
        );
        $dateString = $newDate->format('Y-m-d');
        return $dateString;
    }
        
    public function testListDemoFilesDailyALLALL()
    {
        $assistant = $this->makeMailerAssitant(0);
        $dateString = $this->getTomorrowDateString();
        $filesToMake = $assistant->listDemoFiles("daily", "ALL", "ALL");
        $this->assertEquals(4, count($filesToMake));
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_'.$dateString.'.DOCX',
             "date_string"=> $dateString,
             "source" => "CNBB",
             "format" => "DOCX",
            ],
            $filesToMake[0]
        );
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_'.$dateString.'.PDF',
             "date_string"=> $dateString,
             "source" => "CNBB",
             "format" => "PDF",
            ],
            $filesToMake[1]
        );
        
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_'.$dateString.'.DOCX',
             "date_string"=> $dateString,
             "source" => "Igreja_Santa_Ines",
             "format" => "DOCX",
            ],
            $filesToMake[2]
        );
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_'.$dateString.'.PDF',
             "date_string"=> $dateString,
             "source" => "Igreja_Santa_Ines",
             "format" => "PDF",
            ],
            $filesToMake[3]
        );

    }

    public function testListDemoFilesDailyPDF()
    {
        $assistant = $this->makeMailerAssitant(0);
        $dateString = $this->getTomorrowDateString();
        $filesToMake = $assistant->listDemoFiles("daily", "ALL", "PDF");
        $this->assertEquals(2, count($filesToMake));
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_'.$dateString.'.PDF',
             "date_string"=> $dateString,
             "source" => "CNBB",
             "format" => "PDF",
            ],
            $filesToMake[0]
        );
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_'.$dateString.'.PDF',
             "date_string"=> $dateString,
             "source" => "Igreja_Santa_Ines",
             "format" => "PDF",
            ],
            $filesToMake[1]
        );
        $filesToMake = $assistant->listDemoFiles("daily", "CNBB", "PDF");
        $this->assertEquals(1, count($filesToMake));
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_'.$dateString.'.PDF',
             "date_string"=> $dateString,
             "source" => "CNBB",
             "format" => "PDF",
            ],
            $filesToMake[0]
        );
        $filesToMake = $assistant->listDemoFiles(
            "daily",
            "Igreja_Santa_Ines",
            "PDF"
        );
        $this->assertEquals(1, count($filesToMake));
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_'.$dateString.'.PDF',
             "date_string"=> $dateString,
             "source" => "Igreja_Santa_Ines",
             "format" => "PDF",
            ],
            $filesToMake[0]
        );
    }

    public function testListDemoFilesDailyDOCX()
    {
        $assistant = $this->makeMailerAssitant(0);
        $dateString = $this->getTomorrowDateString();
        $filesToMake = $assistant->listDemoFiles("daily", "ALL", "DOCX");
        $this->assertEquals(2, count($filesToMake));
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_'.$dateString.'.DOCX',
             "date_string"=> $dateString,
             "source" => "CNBB",
             "format" => "DOCX",
            ],
            $filesToMake[0]
        );
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_'.$dateString.'.DOCX',
             "date_string"=> $dateString,
             "source" => "Igreja_Santa_Ines",
             "format" => "DOCX",
            ],
            $filesToMake[1]
        );
        $filesToMake = $assistant->listDemoFiles("daily", "CNBB", "DOCX");
        $this->assertEquals(1, count($filesToMake));
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_'.$dateString.'.DOCX',
             "date_string"=> $dateString,
             "source" => "CNBB",
             "format" => "DOCX",
            ],
            $filesToMake[0]
        );
        $filesToMake = $assistant->listDemoFiles(
            "daily",
            "Igreja_Santa_Ines",
            "DOCX"
        );
        $this->assertEquals(1, count($filesToMake));
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_'.$dateString.'.DOCX',
             "date_string"=> $dateString,
             "source" => "Igreja_Santa_Ines",
             "format" => "DOCX",
            ],
            $filesToMake[0]
        );
    }

    private function getMondayDateString()
    {
        $newDate = date("Y-m-d", strtotime("Monday this week"));
        return $newDate;
    }
    
    public function testListDemoFilesWeeklyALLALL()
    {
        $assistant = $this->makeMailerAssitant(0);
        $dateString = $this->getMondayDateString();
        $filesToMake = $assistant->listDemoFiles("weekly", "ALL", "ALL");
        $this->assertEquals(28, count($filesToMake));
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_'.$dateString.'.DOCX',
             "date_string"=> $dateString,
             "source" => "CNBB",
             "format" => "DOCX",
            ],
            $filesToMake[0]
        );
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_'.$dateString.'.PDF',
             "date_string"=> $dateString,
             "source" => "CNBB",
             "format" => "PDF",
            ],
            $filesToMake[1]
        );
        
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_'.$dateString.'.DOCX',
             "date_string"=> $dateString,
             "source" => "Igreja_Santa_Ines",
             "format" => "DOCX",
            ],
            $filesToMake[2]
        );
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_'.$dateString.'.PDF',
             "date_string"=> $dateString,
             "source" => "Igreja_Santa_Ines",
             "format" => "PDF",
            ],
            $filesToMake[3]
        );

    }

    public function testListDemoFilesWeeklyPDF()
    {
        $assistant = $this->makeMailerAssitant(0);
        $dateString = $this->getMondayDateString();
        $filesToMake = $assistant->listDemoFiles("weekly", "ALL", "PDF");
        $this->assertEquals(14, count($filesToMake));
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_'.$dateString.'.PDF',
             "date_string"=> $dateString,
             "source" => "CNBB",
             "format" => "PDF",
            ],
            $filesToMake[0]
        );
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_'.$dateString.'.PDF',
             "date_string"=> $dateString,
             "source" => "Igreja_Santa_Ines",
             "format" => "PDF",
            ],
            $filesToMake[1]
        );
        $filesToMake = $assistant->listDemoFiles("weekly", "CNBB", "PDF");
        $this->assertEquals(7, count($filesToMake));
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_'.$dateString.'.PDF',
             "date_string"=> $dateString,
             "source" => "CNBB",
             "format" => "PDF",
            ],
            $filesToMake[0]
        );
        $filesToMake = $assistant->listDemoFiles(
            "weekly",
            "Igreja_Santa_Ines",
            "PDF"
        );
        $this->assertEquals(7, count($filesToMake));
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_'.$dateString.'.PDF',
             "date_string"=> $dateString,
             "source" => "Igreja_Santa_Ines",
             "format" => "PDF",
            ],
            $filesToMake[0]
        );
    }

    public function testListDemoFilesWeeklyDOCX()
    {
        $assistant = $this->makeMailerAssitant(0);
        $dateString = $this->getMondayDateString();
        $filesToMake = $assistant->listDemoFiles("weekly", "ALL", "DOCX");
        $this->assertEquals(14, count($filesToMake));
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_'.$dateString.'.DOCX',
             "date_string"=> $dateString,
             "source" => "CNBB",
             "format" => "DOCX",
            ],
            $filesToMake[0]
        );
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_'.$dateString.'.DOCX',
             "date_string"=> $dateString,
             "source" => "Igreja_Santa_Ines",
             "format" => "DOCX",
            ],
            $filesToMake[1]
        );
        $filesToMake = $assistant->listDemoFiles("weekly", "CNBB", "DOCX");
        $this->assertEquals(7, count($filesToMake));
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_'.$dateString.'.DOCX',
             "date_string"=> $dateString,
             "source" => "CNBB",
             "format" => "DOCX",
            ],
            $filesToMake[0]
        );
        $filesToMake = $assistant->listDemoFiles(
            "weekly",
            "Igreja_Santa_Ines",
            "DOCX"
        );
        $this->assertEquals(7, count($filesToMake));
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_'.$dateString.'.DOCX',
             "date_string"=> $dateString,
             "source" => "Igreja_Santa_Ines",
             "format" => "DOCX",
            ],
            $filesToMake[0]
        );
    }

    public function testListDemoFilesBiWeeklyALLALL()
    {
        $assistant = $this->makeMailerAssitant(0);
        $dateString = $this->getMondayDateString();
        $filesToMake = $assistant->listDemoFiles("biweekly", "ALL", "ALL");
        $this->assertEquals(56, count($filesToMake));
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_'.$dateString.'.DOCX',
             "date_string"=> $dateString,
             "source" => "CNBB",
             "format" => "DOCX",
            ],
            $filesToMake[0]
        );
        $this->assertEquals(
            ["file_name" => 'doc-CNBB_'.$dateString.'.PDF',
             "date_string"=> $dateString,
             "source" => "CNBB",
             "format" => "PDF",
            ],
            $filesToMake[1]
        );
        
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_'.$dateString.'.DOCX',
             "date_string"=> $dateString,
             "source" => "Igreja_Santa_Ines",
             "format" => "DOCX",
            ],
            $filesToMake[2]
        );
        $this->assertEquals(
            ["file_name" => 'doc-Igreja_Santa_Ines_'.$dateString.'.PDF',
             "date_string"=> $dateString,
             "source" => "Igreja_Santa_Ines",
             "format" => "PDF",
            ],
            $filesToMake[3]
        );
    }
}

<?php
namespace App\Tests\Util;

use App\Application\Sonata\UserBundle\Entity\User;
use App\Entity\EmailSubscription;
use App\Util\MailerAssistant;
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
        string $period = "1"
    ) {
        $subscription = $this->createMock(EmailSubscription::class);
        $subscription->expects($this->any())
            ->method('getIsActive')
            ->willReturn($isSubscribed);
        $subscription->expects($this->any())
            ->method('getPeriodicity')
            ->willReturn($period);
        
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
    
    public function testGetEnabledUsersVoid()
    {
        $assistant = new MailerAssistant($this->mockEntityManagerVoid());
        $this->assertEquals([], $assistant->getEnabledUsers());
    }

    public function testGetEnabledUsers()
    {
        $assistant = new MailerAssistant($this->mockEntityManager(0));
        $this->assertEquals([], $assistant->getEnabledUsers());
        $assistant = new MailerAssistant($this->mockEntityManager(2));
        $this->assertEquals(2, count($assistant->getEnabledUsers()));
    }

    public function testGetSubscribedUsers()
    {
        $assistant = new MailerAssistant($this->mockEntityManager(0,2));
        $this->assertEquals([], $assistant->getSubscribedUsers([]));
        $assistant = new MailerAssistant($this->mockEntityManager(0,0));
        $expectedUsers = $assistant->getSubscribedUsers(
            [$this->mockUser(true, true)]
        );
                       
        $this->assertTrue(
            $expectedUsers[0]->getEmailSubscription()->getIsActive()
        );
    }

    public function testGetSubscribedUsersWeekly()
    {
        $assistant = new MailerAssistant($this->mockEntityManager(0,0));
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

    public function testListFilesToMake()
    {
        $assistant = new MailerAssistant($this->mockEntityManager(0, 2));
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
        $assistant = new MailerAssistant($this->mockEntityManager(0, 2));
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
}

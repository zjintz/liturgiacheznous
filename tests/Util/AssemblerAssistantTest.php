<?php
namespace App\Tests\Util;

use App\Entity\LiturgyText;
use App\Entity\Liturgy;
use App\Util\AssemblerAssistant;
use App\Repository\LiturgyRepository;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectRepository;


class AssemblerAssistantTest extends TestCase
{
    public function testAddDetails()
    {
        $liturgy = new Liturgy();
        $liturgy->setDescription("renewed title");        
        $testDate = new \DateTime("2019-08-08");
        $liturgyRepository = $this->createMock(LiturgyRepository::class);
        $liturgyRepository->expects($this->any())
            ->method('findOneBy')
            ->willReturn($liturgy);

        $assistant = new AssemblerAssistant($liturgyRepository);
        $liturgyText = new LiturgyText();
        $testDate = new \DateTime("2019-08-08");
        $liturgyText->setDate($testDate);

        $newLitText = $assistant->addDetails($liturgyText);

        $this->assertEquals("renewed title", $newLitText->getDayTitle());
    }

    public function testAddDetailsNullDesc()
    {
        $liturgy = new Liturgy();
        $liturgy->setLiturgyDay("Use Liturgy Day");
        $testDate = new \DateTime("2019-08-08");
        $liturgyRepository = $this->createMock(LiturgyRepository::class);
        $liturgyRepository->expects($this->any())
            ->method('findOneBy')
            ->willReturn($liturgy);

        $assistant = new AssemblerAssistant($liturgyRepository);
        $liturgyText = new LiturgyText();
        $testDate = new \DateTime("2019-08-08");
        $liturgyText->setDate($testDate);

        $newLitText = $assistant->addDetails($liturgyText);
        $this->assertEquals("Use Liturgy Day", $newLitText->getDayTitle());
    }
}

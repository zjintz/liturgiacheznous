<?php
namespace App\Tests\Util;

use App\Entity\Liturgy;
use App\Entity\LiturgyReading;
use App\Entity\LiturgySection;
use App\Entity\LiturgyText;
use App\Util\AssemblerAssistant;
use App\Repository\LiturgyRepository;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectRepository;


class AssemblerAssistantTest extends TestCase
{

    protected function mockLiturgyRepository($liturgy)
    {
        $liturgyRepository = $this->createMock(LiturgyRepository::class);
        $liturgyRepository->expects($this->any())
            ->method('findOneBy')
            ->willReturn($liturgy);
        return $liturgyRepository;
    }
    
    public function testAddDetails()
    {
        $liturgy = new Liturgy();
        $liturgy->setDescription("renewed title");        
        $testDate = new \DateTime("2019-08-08");
        $liturgyRepository = $this->mockLiturgyRepository($liturgy);
        $assistant = new AssemblerAssistant($liturgyRepository);

        //creating the liturgytext
        $liturgyText = new LiturgyText();
        $testDate = new \DateTime("2019-08-08");
        $liturgyText->setDate($testDate);
        //temporal section
        $temporalSection = new LiturgySection();
        $firstReading = new LiturgyReading();
        $firstReading->setReference("Lv 23,1.4-11.15-16.27.34b-37");
        $secondReading = new LiturgyReading();
        $secondReading->setReference("Dt 4,32-40");
        $temporalSection->setFirstReading($firstReading);
        $temporalSection->setSecondReading($secondReading);
        $liturgyText->setTemporalSection($temporalSection);

        //santoral section
        $santoralSection = new LiturgySection();
        $firstReading = new LiturgyReading();
        $firstReading->setReference("Js 3,7-10a.11.13-17");
        $secondReading = new LiturgyReading();
        $secondReading->setReference("Ex 40,16-21.34-38 ");
        $santoralSection->setFirstReading($firstReading);
        $santoralSection->setSecondReading($secondReading);
        $liturgyText->setSantoralSection($santoralSection);

        //lets do the magic
        $newLitText = $assistant->addDetails($liturgyText);
        //now lets assert
        $this->assertEquals("renewed title", $newLitText->getDayTitle());
        $this->assertEquals(
            "Levítico",
            $newLitText->getTemporalSection()->getFirstReading()->getBookName()
        );
        $this->assertEquals(
            "Deuteronômio",
            $newLitText->getTemporalSection()->getSecondReading()->getBookName()
        );
        $this->assertEquals(
            "Josué",
            $newLitText->getSantoralSection()->getFirstReading()->getBookName()
        );
        $this->assertEquals(
            "Êxodo",
            $newLitText->getSantoralSection()->getSecondReading()->getBookName()
        );
    }

    public function testAddDetailsNullDesc()
    {
        $liturgy = new Liturgy();
        $liturgy->setLiturgyDay("Use Liturgy Day");
        $testDate = new \DateTime("2019-08-08");
        $liturgyRepository = $this->mockLiturgyRepository($liturgy);
        $assistant = new AssemblerAssistant($liturgyRepository);
        $liturgyText = new LiturgyText();
        $testDate = new \DateTime("2019-08-08");
        $liturgyText->setDate($testDate);
        $newLitText = $assistant->addDetails($liturgyText);
        $this->assertEquals("Use Liturgy Day", $newLitText->getDayTitle());
    }

    public function testAddDetailsNullReadings()
    {
        $liturgy = new Liturgy();
        $liturgy->setDescription("renewed title");        
        $testDate = new \DateTime("2019-08-08");
        $liturgyRepository = $this->mockLiturgyRepository($liturgy);
        $assistant = new AssemblerAssistant($liturgyRepository);

        //creating the liturgytext
        $liturgyText = new LiturgyText();
        $testDate = new \DateTime("2019-08-08");
        $liturgyText->setDate($testDate);
        //temporal section
        $temporalSection = new LiturgySection();
        $firstReading = new LiturgyReading();
        $firstReading->setReference("Lv 23,1.4-11.15-16.27.34b-37");
        $temporalSection->setFirstReading($firstReading);
        $liturgyText->setTemporalSection($temporalSection);

        //santoral section
        $santoralSection = new LiturgySection();
        $liturgyText->setSantoralSection($santoralSection);

        //lets do the magic
        $newLitText = $assistant->addDetails($liturgyText);
        //now lets assert
        $this->assertEquals("renewed title", $newLitText->getDayTitle());
        $this->assertEquals(
            "Levítico",
            $newLitText->getTemporalSection()->getFirstReading()->getBookName()
        );
    }
}

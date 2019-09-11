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
    
    protected function makeTemporalSection()
    {
        $temporalSection = new LiturgySection();
        $firstReading = new LiturgyReading();
        $firstReading->setReference("Lv 23,1.4-11.15-16.27.34b-37");
        $secondReading = new LiturgyReading();
        $secondReading->setReference("Dt 4,32-40");
        $temporalSection->setFirstReading($firstReading);
        $temporalSection->setSecondReading($secondReading);
        return $temporalSection;
    }

    protected function makeSantoralSection()
    {
        $santoralSection = new LiturgySection();
        $firstReading = new LiturgyReading();
        $firstReading->setReference("Js 3,7-10a.11.13-17");
        $secondReading = new LiturgyReading();
        $secondReading->setReference("Ex 40,16-21.34-38 ");
        $santoralSection->setFirstReading($firstReading);
        $santoralSection->setSecondReading($secondReading);
        $santoralSection->setLoadStatus("Success");
        return $santoralSection;
    }
    
    public function testAddDetails()
    {
        $liturgy = new Liturgy();
        $liturgy->setDescription("renewed title");
        $liturgy->setAlleluiaVerse(
            "Tu és Pedro e sobre esta pedra, eu irei construir minha Igreja, e as portas do inferno não irão derrotá-la. "
        );
        $liturgy->setAlleluiaReference("Mt 16,18");        
        $testDate = new \DateTime("2019-08-08");
        $liturgyRepository = $this->mockLiturgyRepository($liturgy);
        $assistant = new AssemblerAssistant($liturgyRepository);

        //creating the liturgytext
        $liturgyText = new LiturgyText();
        $testDate = new \DateTime("2019-08-08");
        $liturgyText->setDate($testDate);
        //temporal section
        $temporalSection = $this->makeTemporalSection();
        $liturgyText->setTemporalSection($temporalSection);
        //santoral section
        $santoralSection = $this->makeSantoralSection();
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
        $this->assertEquals(
            "Mt 16,18",
            $newLitText->getGospelAcclamation()->getReference()
        );
        $this->assertEquals(
            "Tu és Pedro e sobre esta pedra, eu irei construir minha Igreja, e as portas do inferno não irão derrotá-la. ",
            $newLitText->getGospelAcclamation()->getVerse()
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

    public function testNoAcclamation()
    {
        $liturgy = new Liturgy();
        $liturgy->setDescription("renewed title");        
        $testDate = new \DateTime("2019-08-05");
        $liturgyRepository = $this->mockLiturgyRepository($liturgy);
        $assistant = new AssemblerAssistant($liturgyRepository);

        //creating the liturgytext
        $liturgyText = new LiturgyText();
        $liturgyText->setDate($testDate);
        //lets do the magic
        $newLitText = $assistant->addDetails($liturgyText);
        //now lets assert

        $this->assertEquals(
            "XXXXXXX",
            $newLitText->getGospelAcclamation()->getReference()
        );
        $this->assertEquals(
            "XXXXXXX",
            $newLitText->getGospelAcclamation()->getVerse()
        );
    }

    public function testFixSantaInesDetailsSunday()
    {
        $liturgy = new Liturgy();
        $liturgyRepository = $this->mockLiturgyRepository($liturgy);
        $assistant = new AssemblerAssistant($liturgyRepository);
        
        //creating the liturgytext
        $liturgyText = new LiturgyText();
        $testDate = new \DateTime("2019-09-08");
        $liturgyText->setDate($testDate);
        //temporal section
        $temporalSection = $this->makeTemporalSection();
        $liturgyText->setTemporalSection($temporalSection);
        
        //santoral section
        $santoralSection = $this->makeSantoralSection();
        $liturgyText->setSantoralSection($santoralSection);
        
        

        //lets do the magic
        $newLitText = $assistant->fixSantaInesDetails($liturgyText);
        //now lets assert
        $this->assertNotNull(
            $newLitText->getTemporalSection()
        );
        //Because is Sunday it desn't need to have a santoral section.
        $this->assertEquals(
            "Not_Found",
            $newLitText->getSantoralSection()->getLoadStatus()
        );

        //Now is Monday, it has to have the santoral section!
        $testDate = new \DateTime("2019-09-09");

        $liturgyText->setDate($testDate);
        $liturgyText->setSantoralSection($santoralSection);
        $newLitText = $assistant->fixSantaInesDetails($liturgyText);
        $this->assertNotNull(
            $newLitText->getTemporalSection()
        );
        $this->assertNotNull(
            $newLitText->getSantoralSection()
        );
        $this->assertEquals(
            "Success",
            $newLitText->getSantoralSection()->getLoadStatus()
        ); 
    }

    protected function assertSpecialCase($liturgy)
    {
        $testDate = new \DateTime("2019-08-29");
        $liturgyRepository = $this->mockLiturgyRepository($liturgy);
        $assistant = new AssemblerAssistant($liturgyRepository);
        
        //creating the liturgytext
        $liturgyText = new LiturgyText();
        $testDate = new \DateTime("2019-08-29");
        $liturgyText->setDate($testDate);
        //temporal section
        $temporalSection = $this->makeTemporalSection();
        $liturgyText->setTemporalSection($temporalSection);
        
        //santoral section
        $santoralSection = $this->makeSantoralSection();
        $liturgyText->setSantoralSection($santoralSection);

        //lets do the magic
        $newLitText = $assistant->fixSantaInesDetails($liturgyText);
        //now lets assert
        $this->assertEquals(
            $santoralSection,
            $newLitText->getTemporalSection()
        );
        //now lets assert
        $this->assertNotNull(
            $newLitText->getSantoralSection()
        );
        //Because is Sunday it desn't need to have a santoral section.
        $this->assertEquals(
            "Not_Found",
            $newLitText->getSantoralSection()->getLoadStatus()
        );

        //if is sunday it should keep the temporal and nothing else.
        $testDate = new \DateTime("2019-09-08");
        $liturgyText->setDate($testDate);
        $liturgyText->setSantoralSection($santoralSection);
        $liturgyText->setTemporalSection($temporalSection);
        $newLitText = $assistant->fixSantaInesDetails($liturgyText);
        //now lets assert
        $this->assertEquals(
            $liturgyText->getTemporalSection(),
            $newLitText->getTemporalSection()
        );
           //Because is Sunday it desn't need to have a santoral section.
        $this->assertEquals(
            "Not_Found",
            $newLitText->getSantoralSection()->getLoadStatus()
        );
    }
    
    public function testFixSantaInesDetailsMemorial()
    {
        $liturgy = new Liturgy();
        $liturgy->setIsMemorial(true);
        $this->assertSpecialCase($liturgy);
    }
    
    public function testFixSantaInesDetailsSolemnity()
    {
        $liturgy = new Liturgy();
        $liturgy->setIsSolemnity(true);
        $this->assertSpecialCase($liturgy);
    }

    public function testFixSantaInesDetailsCelebration()
    {
        $liturgy = new Liturgy();
        $liturgy->setIsCelebration(true);
        $this->assertSpecialCase($liturgy);
    }    
}

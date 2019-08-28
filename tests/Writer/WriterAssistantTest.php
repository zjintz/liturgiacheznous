<?php
namespace App\Tests\Writer;

use App\Entity\GospelAcclamation;
use App\Entity\GospelReading;
use App\Entity\LiturgyText;
use App\Entity\LiturgySection;
use App\Writer\WriterAssistant;
use App\Factory\GospelReadingFactory;
use App\Factory\PsalmReadingFactory;
use App\Factory\LiturgyReadingFactory;
use PHPUnit\Framework\TestCase;

class WriterAssistantTest extends TestCase
{
    protected $basicLiturgy;
    protected $biReadingLiturgy;
    protected $fullSantoralLiturgy;
    protected $singleSantoralLiturgy;
    protected $fullSantoral2lLiturgy;
    protected $singleSantoral2lLiturgy;
    
    protected function setup()
    {
        $this->createBasicLiturgy();
        $this->createBiReadingLiturgy();
        $this->createFullSantoralLiturgy();
        $this->createSingleSantoralLiturgy();
        $this->createFullSantoral2lLiturgy();
        $this->createSingleSantoral2lLiturgy();

    }
    public function testSelectTemplate()
    {
        $assistant = new WriterAssistant();
        $template = $assistant->selectTemplate($this->basicLiturgy, "/tmp");
        $this->assertEquals(
            '/tmp/templates/liturgy/basic_liturgy.docx',
            $template
        );
        $template = $assistant->selectTemplate($this->biReadingLiturgy, "/tmp");
        $this->assertEquals(
            '/tmp/templates/liturgy/double_reading_liturgy.docx',
            $template
        );
        $template = $assistant->selectTemplate($this->fullSantoralLiturgy, "/tmp");
        $this->assertEquals(
            '/tmp/templates/liturgy/full_santoral_liturgy.docx',
            $template
        );
        $template = $assistant->selectTemplate($this->singleSantoralLiturgy, "/tmp");
        $this->assertEquals(
            '/tmp/templates/liturgy/single_santoral_liturgy.docx',
            $template
        );
        $template = $assistant->selectTemplate($this->fullSantoral2lLiturgy, "/tmp");
        $this->assertEquals(
            '/tmp/templates/liturgy/full_santoral_2l_liturgy.docx',
            $template
        );
        $template = $assistant->selectTemplate($this->singleSantoral2lLiturgy, "/tmp");
        $this->assertEquals(
            '/tmp/templates/liturgy/single_santoral_2l_liturgy.docx',
            $template
        );
    }
    
    public function testGetLiturgyArray()
    {
        $assistant = new WriterAssistant();
        $liturgyArray = $assistant->getLiturgyArray($this->basicLiturgy);
        $this->assertCount(15, $liturgyArray);
        $liturgyArray = $assistant->getLiturgyArray($this->biReadingLiturgy);
        $this->assertCount(19, $liturgyArray);

        $liturgyArray = $assistant->getLiturgyArray($this->fullSantoralLiturgy);
        $this->assertCount(26, $liturgyArray);
        $liturgyArray = $assistant->getLiturgyArray($this->singleSantoralLiturgy);
        $this->assertCount(19, $liturgyArray);
        $liturgyArray = $assistant->getLiturgyArray($this->fullSantoral2lLiturgy);
        $this->assertCount(30, $liturgyArray);
        $liturgyArray = $assistant->getLiturgyArray($this->singleSantoral2lLiturgy);
        $this->assertCount(23, $liturgyArray);
    }

    public function testGetPsalmLines()
    {
        $salmoText = <<<EOD
É aquele que caminha sem pecado*
e pratica a justiça fielmente;
que pensa a verdade no seu íntimo *
e não solta em calúnias sua língua.R. 

Que em nada prejudica o seu irmão,*
nem cobre de insultos seu vizinho;
que não dá valor algum ao homem ímpio,*
mas honra os que respeitam o Senhor.R. 

não empresta o seu dinheiro com usura,
nem se deixa subornar contra o inocente.*
Jamais vacilará quem vive assim!R.
EOD;
        $assistant = new WriterAssistant();
        $lines = $assistant->getPsalmLines($salmoText);
        $this->assertCount(3, $lines);
        $firstLine = <<<EOD
É aquele que caminha sem pecado*
e pratica a justiça fielmente;
que pensa a verdade no seu íntimo *
e não solta em calúnias sua língua.
EOD;
        $secondLine = <<<EOD
Que em nada prejudica o seu irmão,*
nem cobre de insultos seu vizinho;
que não dá valor algum ao homem ímpio,*
mas honra os que respeitam o Senhor.
EOD;
        $lastLine = <<<EOD
não empresta o seu dinheiro com usura,
nem se deixa subornar contra o inocente.*
Jamais vacilará quem vive assim!
EOD;
        $this->assertEquals($firstLine, $lines[0]);
        $this->assertEquals($secondLine, $lines[1]);
        $this->assertEquals($lastLine, $lines[2]);
    }

    protected function getAcclamation()
    {
        $acclamation = new GospelAcclamation();
        $acclamation->setVerse("XXXXXXX");
        $acclamation->setReference("XXXXXXX");
        return $acclamation;
    }
    protected function getSection()
    {
        $section = new LiturgySection();
        $section->setLoadStatus("Success");
        $psalmFactory = new PsalmReadingFactory();
        $gospelFactory = new GospelReadingFactory();
        $readingFactory = new LiturgyReadingFactory();

        $reading = $readingFactory->createReading(
            "titulo-refe",
            "texto",
            "intro",
            "subtitulo"
        );
        $psalm = $psalmFactory->createReading(
            "titulo-refegospel",
            "texto-salmo",
            "chorus"
        );
        $gospel = $gospelFactory->createReading(
            "titulo-refe",
            "texto-gospel",
            "intro-gospel",
            "subtitulo-gospel"
        );
        $section->setFirstReading($reading);
        $section->setPsalmReading($psalm);
        $section->setGospelReading($gospel);
        return $section;
    }

    protected function createBasicLiturgy()
    {
        $this->basicLiturgy = new LiturgyText();
        $this->basicLiturgy->setDayTitle("Text title");
        $this->basicLiturgy->setGospelAcclamation($this->getAcclamation());
        $this->basicLiturgy->setDate(new \Datetime("1900-01-01"));
        $this->basicLiturgy->setTemporalSection($this->getSection());
        $emptySection = new LiturgySection();
        $emptySection->setLoadStatus("Not_Found");
        $this->basicLiturgy->setSantoralSection($emptySection);
        
    }
    protected function createFullSantoralLiturgy()
    {
        $this->fullSantoralLiturgy = new LiturgyText();
        $this->fullSantoralLiturgy->setDayTitle("Text title");
        $this->fullSantoralLiturgy->setGospelAcclamation($this->getAcclamation());
        $this->fullSantoralLiturgy->setDate(new \Datetime("1900-01-01"));
        $this->fullSantoralLiturgy->setTemporalSection($this->getSection());
        $this->fullSantoralLiturgy->setSantoralSection($this->getSection());
    }

    protected function createBiReadingLiturgy()
    {
        $this->biReadingLiturgy = new LiturgyText();
        $this->biReadingLiturgy->setDayTitle("Text title");
        $this->biReadingLiturgy->setGospelAcclamation($this->getAcclamation());
        $this->biReadingLiturgy->setDate(new \Datetime("1900-01-01"));
        $temporal = $this->getSection();
        $readingFactory = new LiturgyReadingFactory();

        $reading = $readingFactory->createReading(
            "titulo-refe2",
            "texto2",
            "intro2",
            "subtitulo2"
        );
        $temporal->setSecondReading($reading);
        $this->biReadingLiturgy->setTemporalSection($temporal);
        $emptySection = new LiturgySection();
        $emptySection->setLoadStatus("Not_Found");
        $this->basicLiturgy->setSantoralSection($emptySection);
    }
    protected function createSingleSantoralLiturgy()
    {
        $this->singleSantoralLiturgy = new LiturgyText();
        $this->singleSantoralLiturgy->setDayTitle("Text title");
        $this->singleSantoralLiturgy->setGospelAcclamation($this->getAcclamation());
        $this->singleSantoralLiturgy->setDate(new \Datetime("1900-01-01"));
        $this->singleSantoralLiturgy->setTemporalSection($this->getSection());
        $santoral = new LiturgySection();
        $santoral->setLoadStatus("Success");
        $readingFactory = new LiturgyReadingFactory();
        $reading = $readingFactory->createReading(
            "titulo-santo1",
            "texto1",
            "intro1",
            "subtitulo1"
        );
        $santoral->setFirstReading($reading);
        $this->singleSantoralLiturgy->setSantoralSection($santoral);

    }
    protected function createFullSantoral2lLiturgy()
    {
        $this->fullSantoral2lLiturgy = new LiturgyText();
        $this->fullSantoral2lLiturgy->setDayTitle("Text title");
        $this->fullSantoral2lLiturgy->setGospelAcclamation($this->getAcclamation());
        $this->fullSantoral2lLiturgy->setDate(new \Datetime("1900-01-01"));
        $temporal = $this->getSection();
        $readingFactory = new LiturgyReadingFactory();
        $reading = $readingFactory->createReading(
            "titulo-refe2",
            "texto2",
            "intro2",
            "subtitulo2"
        );
        $temporal->setSecondReading($reading);
        $this->fullSantoral2lLiturgy->setTemporalSection($temporal);
        $this->fullSantoral2lLiturgy->setSantoralSection($this->getSection());
    }
    protected function createSingleSantoral2lLiturgy()
    {
        $this->singleSantoral2lLiturgy = new LiturgyText();
        $this->singleSantoral2lLiturgy->setDayTitle("Text title");
        $this->singleSantoral2lLiturgy->setGospelAcclamation($this->getAcclamation());
        $this->singleSantoral2lLiturgy->setDate(new \Datetime("1900-01-01"));
        $temporal = $this->getSection();
        $readingFactory = new LiturgyReadingFactory();
        $reading = $readingFactory->createReading(
            "titulo-refe2",
            "texto2",
            "intro2",
            "subtitulo2"
        );
        $temporal->setSecondReading($reading);
        $this->singleSantoral2lLiturgy->setTemporalSection($temporal);
        $santoral = new LiturgySection();
        $santoral->setLoadStatus("Success");
        $readingFactory = new LiturgyReadingFactory();
        $reading = $readingFactory->createReading(
            "titulo-santo1",
            "texto1",
            "intro1",
            "subtitulo1"
        );
        $santoral->setFirstReading($reading);
        $this->singleSantoral2lLiturgy->setSantoralSection($santoral);

    }
}

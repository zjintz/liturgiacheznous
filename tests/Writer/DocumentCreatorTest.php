<?php
namespace App\Tests\Writer;

use App\Writer\DocumentCreator;
use App\Entity\LiturgyText;
use App\Entity\LiturgySection;
use App\Factory\GospelReadingFactory;
use App\Factory\PsalmReadingFactory;
use App\Factory\LiturgyReadingFactory;
use PHPUnit\Framework\TestCase;

class DocumentCreatorTest extends TestCase
{
    protected $basicLiturgy;
    protected $fullSantoralLiturgy;
    
    protected function setup()
    {
        $this->createBasicLiturgy();
        $this->createFullSantoralLiturgy();
    }
    public function testCreateDocument()
    {
        $docCreator = new DocumentCreator();
        $filePath = $docCreator->createDocument('DOCX', $this->basicLiturgy, ".");
        $this->assertEquals("./var/cache/generatedDoc.docx", $filePath);
        $this->assertFileExists($filePath);
        $filePath = $docCreator->createDocument('PDF', $this->basicLiturgy, ".");
        $this->assertEquals("./var/cache/generatedDoc.pdf", $filePath);
        $this->assertFileExists($filePath);

        $filePath = $docCreator->createDocument(
            'DOCX',
            $this->fullSantoralLiturgy,
            "."
        );
        $this->assertEquals("./var/cache/generatedDoc.docx", $filePath);
        $this->assertFileExists($filePath);
        $filePath = $docCreator->createDocument(
            'PDF',
            $this->fullSantoralLiturgy,
            "."
        );
        $this->assertEquals("./var/cache/generatedDoc.pdf", $filePath);
        $this->assertFileExists($filePath);
    }

    protected function getSection()
    {
        $section = new LiturgySection();
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
        $this->basicLiturgy->setDate(new \Datetime("1900-01-01"));
        $this->basicLiturgy->setTemporalSection($this->getSection());
    }
    protected function createFullSantoralLiturgy()
    {
        $this->fullSantoralLiturgy = new LiturgyText();
        $this->fullSantoralLiturgy->setDayTitle("Text title");
        $this->fullSantoralLiturgy->setDate(new \Datetime("1900-01-01"));
        $this->fullSantoralLiturgy->setTemporalSection($this->getSection());
        $this->fullSantoralLiturgy->setSantoralSection($this->getSection());
    }

    protected function createBiReadingLiturgy()
    {
        $this->biReadingLiturgy = new LiturgyText();
        $this->biReadingLiturgy->setDayTitle("Text title");
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
    }
}

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
    public function testCreateDocument()
    {
        $litText = new LiturgyText();
        $litText->setDayTitle("Text title");
        $litText->setDate(new \Datetime("1900-01-01"));
        $temporal = new LiturgySection();
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
        $temporal->setFirstReading($reading);
        $temporal->setPsalmReading($psalm);
        $temporal->setGospelReading($gospel);
        $litText->setTemporalSection($temporal);
        $docCreator = new DocumentCreator();
        $filePath = $docCreator->createDocument('DOCX', $litText, ".");
        $this->assertEquals("./var/cache/generatedDoc.docx", $filePath);
        $this->assertFileExists($filePath);
        $filePath = $docCreator->createDocument('PDF', $litText, ".");
        $this->assertEquals("./var/cache/generatedDoc.pdf", $filePath);
        $this->assertFileExists($filePath);
    }
}

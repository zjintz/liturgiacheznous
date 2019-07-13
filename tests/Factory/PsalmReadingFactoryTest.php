<?php
namespace App\Tests\Factory;

use App\Factory\PsalmReadingFactory;
use App\Entity\PsalmReading;
use PHPUnit\Framework\TestCase;

class PsalmReadingFactoryTest extends TestCase
{
    public function testCreateReading()
    {
        $factory = new PsalmReadingFactory();
        $psalmReading = $factory->createReading("", "", "");
        $this->assertEquals("-", $psalmReading->getReference());
    }
}

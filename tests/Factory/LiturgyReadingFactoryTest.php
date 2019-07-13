<?php
namespace App\Tests\Factory;

use App\Factory\LiturgyReadingFactory;
use App\Entity\LiturgyReading;
use PHPUnit\Framework\TestCase;

class LiturgyReadingFactoryTest extends TestCase
{
    public function testCreateReading()
    {
        $factory = new LiturgyReadingFactory();
        $reading = $factory->createReading("", "", "","");
        $this->assertEquals("-", $reading->getReference());
    }
}

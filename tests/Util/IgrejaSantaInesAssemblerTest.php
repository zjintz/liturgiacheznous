<?php
namespace App\Tests\Util;

use App\Repository\LiturgyRepository;
use App\Util\IgrejaSantaInesAssembler;
use PHPUnit\Framework\TestCase;

class IgrejaSantaInesAssemblerTest extends TestCase
{
    public function testAssembleNotFound()
    {
        $liturgyRepository = $this->createMock(LiturgyRepository::class);
        $assembler = new IgrejaSantaInesAssembler($liturgyRepository, "");
        $liturgyText = $assembler->getDocument("180000-01-01", 'pdf');
        $this->assertEquals("Not_Found", $liturgyText);
    }
}

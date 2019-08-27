<?php
namespace App\Tests\Util;

use App\Util\CNBBAssembler;
use App\Repository\LiturgyRepository;
use PHPUnit\Framework\TestCase;


class CNBBAssemblerTest extends TestCase
{
    public function testAssembleNotFound()
    {
        $liturgyRepository = $this->createMock(LiturgyRepository::class);
        $assembler = new CNBBAssembler($liturgyRepository, "");
        $liturgyText = $assembler->getDocument("1900-01-01", 'pdf');
        $this->assertEquals("Not_Found", $liturgyText);
    }
}

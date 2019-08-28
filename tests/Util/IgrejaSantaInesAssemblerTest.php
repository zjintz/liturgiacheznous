<?php
namespace App\Tests\Util;

use App\Util\AssemblerAssistant;
use App\Util\IgrejaSantaInesAssembler;
use PHPUnit\Framework\TestCase;

class IgrejaSantaInesAssemblerTest extends TestCase
{
    public function testAssembleNotFound()
    {
        $assistant = $this->createMock(AssemblerAssistant::class);
        $assembler = new IgrejaSantaInesAssembler("", $assistant);
        $liturgyText = $assembler->getDocument("180000-01-01", 'pdf');
        $this->assertEquals("Not_Found", $liturgyText);
    }
}

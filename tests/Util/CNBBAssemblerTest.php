<?php
namespace App\Tests\Util;

use App\Util\AssemblerAssistant;
use App\Util\CNBBAssembler;
use PHPUnit\Framework\TestCase;


class CNBBAssemblerTest extends TestCase
{
    public function testAssembleNotFound()
    {
        $assistant = $this->createMock(AssemblerAssistant::class);
        $assembler = new CNBBAssembler("", $assistant);
        $liturgyText = $assembler->getDocument("1900-01-01", 'pdf');
        $this->assertEquals("Error: Invalid_Date", $liturgyText);
    }
}

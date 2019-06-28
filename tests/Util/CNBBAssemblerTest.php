<?php
namespace App\Tests\Util;

use App\Util\CNBBAssembler;
use PHPUnit\Framework\TestCase;

class CNBBAssemblerTest extends TestCase
{
    public function testAssembleNotFound()
    {
        $assembler = new CNBBAssembler("");
        $liturgyText = $assembler->getDocument("1900-01-01", 'pdf');
        $this->assertEquals("Not_Found", $liturgyText);
    }
}

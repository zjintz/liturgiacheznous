<?php
namespace App\Tests\Util;

use App\Util\IgrejaSantaInesAssembler;
use PHPUnit\Framework\TestCase;

class IgrejaSantaInesAssemblerTest extends TestCase
{
    public function testAssembleNotFound()
    {
        $assembler = new IgrejaSantaInesAssembler("");
        $liturgyText = $assembler->getDocument("180000-01-01", 'pdf');
        $this->assertEquals("Not_Found", $liturgyText);
    }
}

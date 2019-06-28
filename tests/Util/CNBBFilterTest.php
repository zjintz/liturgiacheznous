<?php
namespace App\Tests\Util;

use App\Util\CNBBFilter;
use PHPUnit\Framework\TestCase;

class CNBBFilterTest extends TestCase
{
    protected function readExample($path)
    {
        $example = fopen($path, "r") or die("Unable to open file!");
        $data =  fread($example, filesize($path));
        fclose($example);
        return $data;
    }
    public function testFilterNotFound()
    {
        $iFilter = new CNBBFilter();
        $data = $this->readExample("./tests/Util/ExampleCNBB_NOT_FOUND.html");
        $liturgyText = $iFilter->filter($data);
        $this->assertEquals("Not_Found", $liturgyText["status"]);
    }
}

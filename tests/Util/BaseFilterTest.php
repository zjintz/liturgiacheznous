<?php

namespace App\Tests\Util;

use PHPUnit\Framework\TestCase;

abstract class BaseFilterTest extends TestCase
{
    public function readExample($path)
    {
        $example = fopen($path, "r") or die("Unable to open file!");
        $data =  fread($example, filesize($path));
        fclose($example);
        return $data;
    }
}

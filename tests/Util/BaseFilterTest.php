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
    
    protected function assertReading(
        $title, $subtitle, $intro, $text, $reference, $reading
    ) {
        $this->assertEquals($title, $reading->getTitle());
        $this->assertEquals($subtitle, $reading->getSubtitle());
        $this->assertEquals($intro, $reading->getIntroduction());
        $this->assertEquals($text, $reading->getText());
        $this->assertEquals($reference, $reading->getReference());
    }
    protected function assertGospelReading(
        $title,
        $subtitle,
        $intro,
        $text,
        $author,
        $reading
    ) {
        $this->assertEquals($title, $reading->getTitle());
        $this->assertEquals($subtitle, $reading->getSubtitle());
        $this->assertEquals($intro, $reading->getIntroduction());
        $this->assertEquals($text, $reading->getText());
        $this->assertEquals($author, $reading->getAuthor());
    }

    protected function assertPsalm($title, $chorus, $text, $reading)
    {
        $this->assertEquals($title, $reading->getTitle());
        $this->assertEquals($chorus, $reading->getChorus());
        $this->assertEquals($text, $reading->getText());
    }
}

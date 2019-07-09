<?php

namespace App\Factory;

use App\Entity\Reading;

/**
 * \brief Base for any type of reading factories.
 *
 *
 */
abstract class ReadingFactory
{
    abstract public function createReading(
        $title = "",
        $text = "",
        $intro = "",
        $subtitle = ""
    ) :Reading;

    protected function extractReference($title):?string
    {
        $pos = strpos($title, "-");
        if ($pos === false) {
            return null;
        }
        $reference = substr($title, ($pos+2));
        $pos = strpos($reference, "ANO IMPAR");
        if ($pos !== false) {
            $reference = substr($reference, $pos+10);
            return $reference;           
        }
        $pos = strpos($reference, "ANO C");
        if ($pos !== false) {
            $reference = substr($reference, $pos+6);
            return $reference;           
        }
        $pos = strpos($reference, "SANTORAL");
        if ($pos !== false) {
            $reference = substr($reference, $pos+9);
            return $reference;           
        }
        return $reference;

    }
}

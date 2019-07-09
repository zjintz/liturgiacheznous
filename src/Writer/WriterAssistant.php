<?php

namespace App\Writer;

/**
 * \brief This class does some functions to assist the DocumentCreator.
 *
 */
class WriterAssistant
{
    public function getPsalmLines($text)
    {
        $lines = array();
        $preLines = explode("R.", trim($text));
        foreach ($preLines as $line) {
            if (preg_match('/\S/', $line)) { //check is not empty
                $lines[] = trim($line);
            }
        }
        return $lines;
    }
}

<?php

namespace App\Util;

use App\Util\AbstractAssembler;
use App\Util\IgrejaSantaInesFilter;

// Include the requires classes of Phpword
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

/**
 * \brief      Assembles documents from the CNBBA source.
 *
 *
 */
class IgrejaSantaInesAssembler extends AbstractAssembler
{
    private $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    
    // Force Extending class to define this method
    protected function genSourceRoute($liturgyDate)
    {
        $miniDate= str_replace("-", "", $liturgyDate);
        $liturgyRoute = "http://www.igrejasantaines.com/liturgia/?h=".$miniDate;
        return $liturgyRoute;
    }

    protected function assemble($data)
    {
        $textFilter = new IgrejaSantaInesFilter();
        $filteredText = $textFilter->filter($data);


        // Create a new Word document
        $phpWord = new PhpWord();

        /* Note: any element you append to a document must reside inside of a Section. */

        // Adding an empty Section to the document...
        $section = $phpWord->addSection();
        // Adding Text element to the Section having font styled by default...
        $section->addText(
            '"Learn from yesterday, live for today, hope for tomorrow. '
            . 'The important thing is not to stop questioning." '
            . '(Albert Einstein)'
        );

        // Saving the document as OOXML file...
        $objWriter = IOFactory::createWriter($phpWord, 'RTF');

        $filePath = $this->projectDir.'/var/cache/generatedDoc.rtf';
        // Write file into path
        $objWriter->save($filePath);
        return $filePath;
    }
}

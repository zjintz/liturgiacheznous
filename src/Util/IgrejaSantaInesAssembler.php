<?php

namespace App\Util;

use App\Util\AbstractAssembler;
use App\Util\AssemblerAssistant;
use App\Util\IgrejaSantaInesFilter;
use App\Repository\LiturgyRepository;

/**
 * \brief      Assembles documents from the CNBBA source.
 *
 *
 */
class IgrejaSantaInesAssembler extends AbstractAssembler
{
    private $liturgyRepository;
    private $projectDir;

    public function __construct(
        string $projectDir,
        AssemblerAssistant $assistant
    ) {
        $this->assistant = $assistant;
        $this->projectDir = $projectDir;
    }
    // Force Extending class to define this method
    protected function genSourceRoute($liturgyDate)
    {
        $miniDate= str_replace("-", "", $liturgyDate);
        $liturgyRoute = "http://www.igrejasantaines.com/liturgia/?h=".$miniDate;
        return $liturgyRoute;
    }

    protected function assemble($data, $format = "rtf", $liturgyDate = "")
    {
        $textFilter = new IgrejaSantaInesFilter();
        $liturgyText = $textFilter->filter($data, $liturgyDate);
        if( $liturgyText->getLoadStatus() === "Not_Found" )
        {
            return $liturgyText->getLoadStatus();
        }
        $liturgyText= $this->assistant->addDetails($liturgyText);
        $liturgyText= $this->assistant->fixSantaInesDetails($liturgyText);
        return $this->createDocument($format, $liturgyText, $this->projectDir);
    }
}

<?php

namespace App\Util;

use App\Util\AbstractAssembler;
use App\Util\IgrejaSantaInesFilter;



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

    protected function assemble($data, $format = "rtf")
    {
        $textFilter = new IgrejaSantaInesFilter();
        $litText = $textFilter->filter($data);
        if( $litText->getLoadStatus() === "Not_Found" )
        {
            return $litText->getLoadStatus();
        }
        return $this->createDocument($format, $litText, $this->projectDir);
    }
}

<?php

namespace App\Util;

use App\Util\AbstractAssembler;
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
        LiturgyRepository $liturgyRepository,
        string $projectDir
    ) {
        $this->liturgyRepository = $liturgyRepository;
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
        $litText = $textFilter->filter($data, $liturgyDate);
        if( $litText->getLoadStatus() === "Not_Found" )
        {
            return $litText->getLoadStatus();
        }
        $litDate = new \DateTime($liturgyDate);
        $liturgy = $this->liturgyRepository->findOneByDate($litDate);
        $description = $liturgy->getDescription();
        if(is_null($description)) {
            $description = $liturgy->getLiturgyDay();
        }
        $litText->setDayTitle($description);
        return $this->createDocument($format, $litText, $this->projectDir);
    }
}

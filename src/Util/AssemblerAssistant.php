<?php

namespace App\Util;

use App\Entity\LiturgyText;
use App\Repository\LiturgyRepository;


/**
 * \brief      Assembles documents from the CNBBA source.
 *
 *
 */
class AssemblerAssistant
{
    private $liturgyRepository;
    
    public function __construct( LiturgyRepository $liturgyRepository)
    {
        $this->liturgyRepository = $liturgyRepository;
    }

    public function addDetails(LiturgyText $liturgyText)
    {
        $liturgy = $this->liturgyRepository->findOneBy(
            ['date' => $liturgyText->getDate()]
        );
        $description = $liturgy->getDescription();
        if(is_null($description)) {
            $description = $liturgy->getLiturgyDay();
        }
        $liturgyText->setDayTitle($description);
        return $liturgyText;
    }

}

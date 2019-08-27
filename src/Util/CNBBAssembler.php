<?php

namespace App\Util;

use App\Util\AbstractAssembler;
use App\Util\CNBBFilter;
use App\Repository\LiturgyRepository;

/**
 * \brief      Assembles documents from the CNBBA source.
 *
 *
 */
class CNBBAssembler extends AbstractAssembler
{
    private $projectDir;
    private $liturgyRepository;
    
    public function __construct(
        LiturgyRepository $liturgyRepository,
        string $projectDir
    ){
        $this->liturgyRepository = $liturgyRepository;
        $this->projectDir = $projectDir;
    }
    // Force Extending class to define this method
    protected function genSourceRoute($liturgyDate)
    {
        $cnbb = "http://liturgiadiaria.cnbb.org.br/app/user/user/UserView.php";
        $pieces = explode("-", $liturgyDate);
        $addThis = "?ano=".$pieces[0]."&mes=".$pieces[1]."&dia=".$pieces[2];
        $url = $cnbb.$addThis;
        return $url;
    }

    protected function assemble($data, $format = 'rtf' , $liturgyDate = "")
    {
        $textFilter = new CNBBFilter();
        $liturgyText = $textFilter->filter($data, $liturgyDate);
        if ($liturgyText->getLoadStatus() === "Not_Found")
        {
            return $liturgyText->getLoadStatus();
        }
        $litDate = new \DateTime($liturgyDate);
        $liturgy = $this->liturgyRepository->findOneByDate($litDate);
        $description = $liturgy->getDescription();
        if(is_null($description)) {
            $description = $liturgy->getLiturgyDay();
        }
        $liturgyText->setDayTitle($description);
        return $this->createDocument($format, $liturgyText, $this->projectDir);
    }

}

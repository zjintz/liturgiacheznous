<?php

namespace App\Util;

use App\Util\AbstractAssembler;
use App\Util\AssemblerAssistant;
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
    private $assistant;
    
    public function __construct(string $projectDir, AssemblerAssistant $assistant)
    {
        $this->assistant = $assistant;
        $this->projectDir = $projectDir;
    }
    // Force Extending class to define this method
    protected function genSourceRoute($liturgyDate)
    {
        $cnbb = "https://liturgiadiaria.cnbb.org.br/app/user/user/UserView.php";
        $pieces = explode("-", $liturgyDate);
        $addThis = "?ano=".$pieces[0]."&mes=".$pieces[1]."&dia=".$pieces[2];
        $url = $cnbb.$addThis;
        return $url;
    }

    protected function assemble($data, $format = 'rtf', $liturgyDate = "")
    {
        $textFilter = new CNBBFilter();
        $liturgyText = $textFilter->filter($data, $liturgyDate);
        if (($liturgyText->getLoadStatus()) === "Not_Found")
        {
            return "Not_Found";
        }
        $liturgyText= $this->assistant->addDetails($liturgyText);
        return $this->createDocument($format, $liturgyText, $this->projectDir);
    }

}

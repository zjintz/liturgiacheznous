<?php

namespace App\Util;

use App\Util\AbstractAssembler;

use App\Util\CNBBFilter;

// Include the requires classes of Phpword
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

/**
 * \brief      Assembles documents from the CNBBA source.
 *
 *
 */
class CNBBAssembler extends AbstractAssembler
{
    private $projectDir;

    public function __construct(string $projectDir)
    {
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

    protected function assemble($data, $format = 'rtf')
    {
        $textFilter = new CNBBFilter();
        $liturgyText = $textFilter->filter($data);
        if ($liturgyText->getLoadStatus() === "Not_Found")
        {
            return $liturgyText->getLoadStatus();
        }
        return $this->createDocument($format, $liturgyText, $this->projectDir);
    }

}

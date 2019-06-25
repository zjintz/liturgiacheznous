<?php

namespace App\Util;

use App\Util\AbstractAssembler;

/**
 * \brief      Assembles documents from the CNBBA source.
 *
 *
 */
class CNBBAssembler extends AbstractAssembler
{
    // Force Extending class to define this method
    protected function genSourceRoute($liturgyDate)
    {
        return "http://liturgiadiaria.cnbb.org.br/app/user/user/UserView.php";
    }

    protected function assemble($data)
    {
        
    }

}

<?php

namespace Support;

use Personnummer\Personnummer;
use Personnummer\PersonnummerException;

class Helper
{


    /**
     * @param $ssn
     *
     * @return string
     *
     * @throws PersonnummerException
     */
    public static function formatSSN($ssn)
    {
        return (new Personnummer($ssn))->format(true);
    }
}

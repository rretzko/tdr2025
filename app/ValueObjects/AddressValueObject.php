<?php

namespace App\ValueObjects;

use App\Models\Address;
use App\Models\Geostate;
use App\Models\Schools\School;

class AddressValueObject
{
    public static function getStringVo(Address $address): string
    {
        $str = $address->address1;
        $str .= (strlen($address->address2) ? ', '.$address->address2 : '');
        $str .= ', '.$address->city;
        $str .= '  '.Geostate::find($address->geostate_id)->abbr;
        $str .= '  '.$address->postal_code;

        return $str;
    }
}

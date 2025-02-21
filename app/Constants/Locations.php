<?php 

namespace App\Constants;

use App\Constants\Constants;

class Locations extends Constants
{
    const OFFICE = 1;
    const WFH = 2;
    
    protected static $filePath = '/hris-option-lists/locations.json';
}

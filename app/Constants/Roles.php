<?php 

namespace App\Constants;

use App\Constants\Constants;

class Roles extends Constants
{
    CONST USER_ROLE = 5;
    CONST ROLE_PATH = '/hris-option-lists/roles.json';

    protected static $filePath = self::ROLE_PATH;
}

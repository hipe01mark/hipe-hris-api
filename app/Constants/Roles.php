<?php 

namespace App\Constants;

use App\Constants\Constants;

class Roles extends Constants
{
    CONST SUPER_ADMIN = 1;
    CONST ADMIN = 2;
    CONST MANAGER = 3;
    CONST TEAM_LEAD = 4;
    CONST USER = 5;
    CONST ROLE_PATH = '/hris-option-lists/roles.json';

    protected static $filePath = self::ROLE_PATH;
}

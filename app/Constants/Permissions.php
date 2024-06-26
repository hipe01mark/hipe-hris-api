<?php 

namespace App\Constants;

use App\Constants\Constants;

class Permissions extends Constants
{
    CONST APPROVER = 1;
    CONST PERMISSION_PATH = '/hris-option-lists/permissions.json';

    protected static $filePath = self::PERMISSION_PATH;
}

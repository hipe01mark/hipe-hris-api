<?php 

namespace App\Constants;

use App\Constants\Constants;

class LeaveStatuses extends Constants
{
    CONST FOR_REVIEW = 1;
    CONST APPROVED = 2;
    CONST DECLINED = 3;

    protected static $filePath = '/hris-option-lists/leave-statuses.json';
}

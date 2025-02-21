<?php

namespace App\Rules;

use App\Services\UserAttendanceService;
use Illuminate\Contracts\Validation\Rule;

class TimeOutRule implements Rule
{
    protected $message;
    protected $userAttendanceService;
    

    /**
     * Create a new rule instance.
     */
    public function __construct(UserAttendanceService $userAttendanceService)
    {
        $this->userAttendanceService = $userAttendanceService;
    }

    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        $userId = auth()->user()->id;
        $todayAttendance = $this->userAttendanceService
            ->getTodayAttendance($userId);

        if (!$todayAttendance || ($todayAttendance && $todayAttendance->time_in === null)) {
            $this->message = 'The user has not yet timed in!';
            return false;
        }

        if ($todayAttendance && $todayAttendance->time_out !== null) {
            $this->message = 'The user has already timed out!';
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return $this->message;
    }
}

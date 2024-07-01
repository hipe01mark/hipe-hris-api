<?php

namespace App\Http\Requests\Api;

use App\Constants\Define\HttpCode;
use App\Constants\Define\HttpStatus;
use App\Rules\TimeInRule;
use App\Rules\TimeOutRule;
use App\Services\UserAttendanceService;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserAttendanceRequest extends FormRequest
{
    protected $userAttendanceService;

    /**
     * Create a new rule instance.
     */
    public function __construct(UserAttendanceService $userAttendanceService)
    {
        $this->userAttendanceService = $userAttendanceService;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $todayDate = function ($attribute, $value, $fail) {
            if ($value !== Carbon::today()->format('Y-m-d')) {
                $fail('The date must be today\'s date.');
            }
        };

        if (in_array(request()->method(), ['POST'])) {
            return [
                'time_in' => [
                    'required',
                    'date_format:Y-m-d',
                    $todayDate,
                    new TimeInRule($this->userAttendanceService)
                ]
            ];
        }

        if (in_array(request()->method(), ['PATCH'])) {
            return [
                'time_out' => [
                    'required',
                    'date_format:Y-m-d',
                    $todayDate,
                    new TimeOutRule($this->userAttendanceService)
                ]
            ];
        }

        return [];
    }
}

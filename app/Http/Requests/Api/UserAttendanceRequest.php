<?php

namespace App\Http\Requests\Api;

use App\Constants\Define\HttpCode;
use App\Constants\Define\HttpStatus;
use App\Rules\AfterSixPM;
use App\Rules\AlreadyTimedIn;
use App\Services\UserAttendanceService;
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
        if (in_array(request()->method(), ['POST'])) {
            return [
                'time_in' => [
                    'required',
                    new AlreadyTimedIn($this->userAttendanceService)
                ],
                'time_out' => [
                    'sometimes',
                    'required'
                ]
            ];
        }

        return [];
    }

    /**
     * Handles validation error
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            $errors = (new ValidationException($validator))->errors();
            throw new HttpResponseException(
                responder()
                    ->error(HttpCode::VALIDATION_FAILED, trans('validation.failed'))
                    ->data([
                        'validation_errors' => $errors
                    ])
                    ->respond(HttpStatus::MISDIRECTED_REQUEST)
            );
        }

        parent::failedValidation($validator);
    }
}

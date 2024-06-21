<?php

namespace App\Http\Requests\Api\Auth;

use App\Constants\Define\HttpCode;
use App\Constants\Define\HttpStatus;
use App\Rules\EmailNotExistRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class ForgotPasswordRequest extends FormRequest
{
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
        return [
            'email' => [
                'required',
                'email',
                new EmailNotExistRule
            ]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'email.unique' => "We can't find a user with that email address."
        ];
    }

    /**
     * Handles validation error
     */
    protected function failedValidation(Validator $validator): void
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

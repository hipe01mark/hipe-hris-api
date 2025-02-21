<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Constants\Define\HttpCode;
use App\Constants\Define\HttpStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Constants\Gender;
use App\Constants\MaritalStatuses;
use App\Constants\Nationalities;
use App\Constants\Religions;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize() : bool
    {
        return true;
    }
    
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules() : array
    {
        $id = $this->route('userId') ?? $this->input('userId');
        
        return [
            'first_name' => 'required|min:3|max:20',
            'middle_name' => 'present|nullable|max:20',
            'last_name' => 'required|min:3|max:20',
            
            'role_id' => [
                'required',
                'integer',
                'exists:roles,id'
            ],

            'branch_id' => [
                'required',
                'integer',
                'exists:branches,id'
            ],

            'department_id' => [
                'required',
                'integer',
                'exists:departments,id'
            ],
            
            'position_id' => [
                'required',
                'integer',
                'exists:positions,id'
            ],

            'status_id' => [
                'required',
                'integer',
                'exists:positions,id'
            ],

            'gender' => [
                'required',
                'integer',
                Rule::in(Gender::toArray(['getId' => true]))
            ],
            'birth_date' => 'required|date_format:Y-m-d',
            'nationality' => [
                'required',
                'integer',
                Rule::in(Nationalities::toArray(['getId' => true]))
            ],
            'religion' => [
                'required',
                'integer',
                Rule::in(Religions::toArray(['getId' => true]))
            ],
            'marital_status' => [
                'required',
                'integer',
                Rule::in(MaritalStatuses::toArray(['getId' => true]))
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id)
            ],
            'mobile_number' =>  [
                'required',
                'min:10',
                'numeric',
                Rule::unique('user_information')->ignore($id, 'user_id')
            ]
        ];
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

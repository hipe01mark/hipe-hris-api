<?php

namespace App\Http\Requests\Api;

use App\Constants\InitialApprovers;
use App\Constants\LeaveTypes;
use App\Rules\DateRangeExistsRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserLeaveRequest extends FormRequest
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
        if (in_array(request()->method(), ['GET'])) {
            return [
                'start_date' => 'required|date',
                'end_date' => 'required|date'
            ];
        }

        if (in_array(request()->method(), ['POST', 'PUT', 'PATCH'])) {
            $dateRangeExists = new DateRangeExistsRule(
                'user_leaves', 'leave', 'start_date', 'end_date', 'user_id'
            );

            return [
                'type' => [
                    'required',
                    'integer',
                    Rule::in(LeaveTypes::toArray(['getId' => true]))
                ],
                'half_day' => 'required|integer',
                'post_meridiem' => 'required|integer',
                'start_date' => [
                    'required',
                    'date',
                    $dateRangeExists
                ],
                'end_date' => [
                    'required',
                    'date',
                    'after_or_equal:start_date',
                    $dateRangeExists
                ],
                'reason' => 'required|min:5',
                'initial_approver' => [
                    'required',
                    'integer',
                    Rule::in(InitialApprovers::toArray(['getId' => true]))
                ],
            ];
        }

        return [];
    }
}

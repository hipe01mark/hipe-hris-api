<?php

namespace App\Http\Requests\Api;

use App\Constants\Permissions;
use App\Services\AuthService;
use Illuminate\Foundation\Http\FormRequest;

class LeaveActionRequest extends FormRequest
{
    public $authService;

    /**
     * Constructor
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->authService
            ->hasPermission(Permissions::toHuman(Permissions::APPROVER));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [];
    }
}

<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class EmailNotExistRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        $doesntExist = User::whereEmail($value)->doesntExist();
        return $doesntExist ? false : true;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return "We can't find a user with that email address.";
    }
}

<?php

namespace App\Rules;

use App\Models\UserLeave;
use Illuminate\Contracts\Validation\Rule;

class DateRangeExistsRule implements Rule
{
    protected $table;
    protected $routeIdName;
    protected $columnStart;
    protected $columnEnd;
    protected $columnUserId;

    /**
     * Create a new rule instance.
     */
    public function __construct(
        string $table,
        string $routeIdName,
        string $columnStart,
        string $columnEnd,
        string $columnUserId = null
    )
    {
        $this->table = $table;
        $this->routeIdName = $routeIdName;
        $this->columnStart = $columnStart;
        $this->columnEnd = $columnEnd;
        $this->columnUserId = $columnUserId;
    }

    /**
     * Determine if the validation rule passes.
     * 
     * @param string $attribute
     * @param mixed $value
     */
    public function passes($attribute, $value): bool
    {
        $routeId = request()->route($this->routeIdName) ?? null;

        $exists = UserLeave::where(function ($query) {
                $query->whereBetween($this->columnStart, [
                    request()[$this->columnStart], 
                    request()[$this->columnEnd]
                ])
                ->orWhereBetween($this->columnEnd, [
                    request()[$this->columnStart], 
                    request()[$this->columnEnd]
                ]);
            })
            ->where(function ($query) {
                if ($this->columnUserId) {
                    $query->where($this->columnUserId, auth()->user()->id);
                }
            })
            ->exists();

        return !$routeId ? !$exists : true;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'The :attribute already exists.';
    }
}

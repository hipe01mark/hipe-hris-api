<?php

namespace App\Models;

use App\Constants\LeaveStatuses;
use App\Constants\LeaveTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLeave extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are not mass assignable.
     * 
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = ['type_desc', 'status_desc'];

    /**
     * Get user associated with the user leave.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)
            ->with(['information']);
    }

    /**
     * Get the description of the leave type.
     */
    public function getTypeDescAttribute()
    {
        return LeaveTypes::toHuman($this->type);
    }

    /**
     * Get the description of the leave type.
     */
    public function getStatusDescAttribute()
    {
        return LeaveStatuses::toHuman($this->status);
    }
}

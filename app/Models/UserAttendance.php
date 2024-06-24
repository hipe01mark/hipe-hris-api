<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAttendance extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are not mass assignable.
     * 
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime:Y-m-d',
        'time_in' => 'datetime:H:i:s',
        'time_out' => 'datetime:H:i:s',
    ];

    /**
     * Get user associated with the user attendance.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

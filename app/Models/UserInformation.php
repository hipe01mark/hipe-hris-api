<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInformation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The primary key of the model
     * 
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['full_name'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'birth_date',
        'hired_date',
        'resigned_date'
    ];

    /**
     * Timestamps for updated_at and created_at.
     * 
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * Get full name of the student
     */
    public function getFullNameAttribute() : string
    {
        $fullNameArray = [
            $this->first_name,
            $this->last_name
        ];

        return implode(' ', $fullNameArray);
    }

    /**
     * Get the user that owns the user information.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

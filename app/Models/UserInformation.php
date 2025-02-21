<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInformation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are not mass assignable.
     * 
     * @var array<int, string>
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
     * @var array<int, string>
     */
    protected $appends = ['full_name'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array<int, string>
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
     * Get full name of the user
     */
    public function getFullNameAttribute(): string
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

    /**
     * Get the addresses associated to the user information.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class, 'user_id');
    }

    /**
     * Get the emergency contacts associated to the user information.
     */
    public function emergencyContacts(): HasMany
    {
        return $this->hasMany(UserEmergencyContact::class);
    }

    /**
     * Get the department associated to the user information.
     */
    public function branch(): HasOne
    {
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }

    /**
     * Get the department associated to the user information.
     */
    public function department(): HasOne
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }

    /**
     * Get the position associated to the user information.
     */
    public function position(): HasOne
    {
        return $this->hasOne(Position::class, 'id', 'position_id');
    }

    /**
     * Get the position associated to the user information.
     */
    public function status(): HasOne
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }
}

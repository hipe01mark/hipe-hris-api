<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attribute that for guards.
     * 
     * @var string
     */
    protected $guard_name = 'api';

    /**
     * The attributes that are not mass assignable.
     * 
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Check if token is expired
     */
    public function isTokenExpired(): bool
    {
        $tokenExpired = 
            $this->email_verified_at !== null && 
            $this->email_verified_at
                 ->addMinutes(config('auth.verification.expire', 60))
                 ->isPast();
                 
        return $tokenExpired;
    }

    /**
     * Get the information associated with the user.
     */
    public function information(): HasOne
    {
        return $this->hasOne(UserInformation::class);
    }

    /**
     * Get the attendance associated with the user
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(UserAttendance::class);
    }

    /**
     * Get the leaves associated with the user
     */
    public function leaves(): HasMany
    {
        return $this->hasMany(UserLeave::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    /**
     * The attributes that are not mass assignable.
     * 
     * @var array<int, string>
     */
    protected $guarded = [];
}

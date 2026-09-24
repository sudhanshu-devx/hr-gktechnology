<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserKycDetail extends Model
{
    protected $fillable = [
    'user_id',
    'aadhaar_number',
    'pan_number',
];

}

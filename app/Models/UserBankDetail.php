<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBankDetail extends Model
{
    protected $fillable = [
    'user_id',
    'bank_name',
    'account_number',
    'ifsc_code',
    'branch_name',
];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class OfferLetter extends Model
{
    protected $fillable = [
        'user_id',
        'employee_name', // ✅ ADD THIS
        'position',
        'employment_type',
        'salary',
        'stipend',
        'start_date',
        'duration',
        'location',
        'pdf_path',
        'generated_by',
        'basic_salary',
    'hra',
    'special_allowance',
    'bonus',
    'pf_deduction',
    'tax_deduction',
    ];

    protected $casts = [
        'start_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}

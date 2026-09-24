<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveType extends Model
{
    protected $fillable = [
        'name',
        'days_per_year',
        'is_paid',
        'day_value',
        'deducts_from_leave_type_id',
    ];

    protected $casts = [
        'is_paid'   => 'boolean',
        'day_value' => 'float',
    ];

    /* ---------------- Relationships ---------------- */

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /**
     * 🔑 This leave type deducts balance from another leave type
     * Example: Half Day → Casual Leave
     */
    public function deductsFrom(): BelongsTo
    {
        return $this->belongsTo(self::class, 'deducts_from_leave_type_id');
    }
}

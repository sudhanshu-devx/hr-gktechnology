<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

class LeaveRequest extends Model
{
    protected $fillable = [
        'user_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'days',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'approved_at' => 'datetime',
        'days'        => 'float',
    ];

    /* ---------------- Relationships ---------------- */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /* ---------------- HARD RULES ---------------- */

    protected static function booted()
    {
        static::saving(function (LeaveRequest $leave) {

            $leave->loadMissing('leaveType');

            // 1️⃣ HALF DAY RULE
            if ((float) $leave->leaveType->day_value === 0.5) {

                if ($leave->start_date != $leave->end_date) {
                    throw ValidationException::withMessages([
                        'end_date' => 'Half-day leave must be for a single day.',
                    ]);
                }

                // FORCE exact value
                $leave->days = 0.5;
            }

            // 2️⃣ FULL DAY RULE
            else {
                $leave->days =
                    Carbon::parse($leave->start_date)
                        ->diffInDays(Carbon::parse($leave->end_date)) + 1;
            }
        });
    }

    /**
     * 🔑 Returns which leave type balance should be deducted
     */
    public function deductionLeaveTypeId(): int
    {
        return $this->leaveType->deducts_from_leave_type_id
            ?? $this->leave_type_id;
    }
}

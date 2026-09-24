<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
    'name',
    'alias_name',                 // ✅ NEW
    'email',
    'company_alias_email',        // ✅ NEW
    'password',
    'department_id',
    'position_id',
    'employee_id',
    'phone',
    'date_of_birth',
    'hire_date',
    'employment_type',
    'status',
    'salary',
    'address',
    'location',
    'emergency_contact_name',
    'emergency_contact_phone',
];


    /**
     * The attributes that should be hidden.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting.
     */

    /**
     * 🔐 Filament panel access (TEMP: allow all)
     * This FIXES the 403 error.
     */
    public function canAccessPanel(Panel $panel): bool
{
    $panelId = $panel->getId(); // admin | hr | employee

    return match ($panelId) {
        'admin'    => $this->hasAnyRole(['admin', 'super_admin','Manager_P',]),
        'hr'       => $this->hasAnyRole(['HR', 'Manager','super_admin','Manager_P','financial_accounting',]),
        'employee' => $this->hasAnyRole(['admin', 'super_admin','employee','Manager_P','Manager','financial_accounting', ]),
        default    => false,
    };
}

    /* =======================
       RELATIONSHIPS
       ======================= */

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function performanceReviews(): HasMany
    {
        return $this->hasMany(PerformanceReview::class);
    }
    /* =======================
   KYC & BANK DETAILS
   ======================= */

public function kyc()
{
    return $this->hasOne(UserKycDetail::class, 'user_id');
}

public function bank()
{
    return $this->hasOne(UserBankDetail::class, 'user_id');
}

protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'salary' => 'decimal:2',
        'date_of_birth' => 'date',
        'hire_date' => 'date',
    ];
}

/* =======================
   ROLE HELPERS
   ======================= */

public function isAdmin(): bool
{
    return $this->hasRole('Super Admin') || $this->hasRole('Admin');
}

public function isHr(): bool
{
    return $this->hasRole('HR');
}

public function isEmployee(): bool
{
    return $this->hasRole('Employee');
}



    /* =======================
       EMPLOYEE ID GENERATION
       ======================= */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->employee_id)) {
                $lastUser = static::orderBy('id', 'desc')->first();
                $nextNumber = 1;

                if ($lastUser && $lastUser->employee_id) {
                    if (preg_match('/^EMP-(\d+)$/', $lastUser->employee_id, $matches)) {
                        $nextNumber = ((int) $matches[1]) + 1;
                    }
                }

                $user->employee_id = 'EMP-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}

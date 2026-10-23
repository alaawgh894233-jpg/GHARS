<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// ادمج التغييرات مع User الحالي عندك (لا تستبدله بالكامل إذا فيه علاقات ثانية)
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    // ⚠️ role و status خارج fillable عمداً: ما بينحطوا إلا بإسناد صريح من الـ Service
    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array // Laravel 11+. في Laravel 10 استخدم: protected $casts = [...]
    {
        return [
            'email_verified_at' => 'datetime',
            'banned_at'         => 'datetime',
            'role'              => UserRole::class,
            'status'            => UserStatus::class,
        ];
    }

    public function isActive(): bool
    {
        return $this->status === UserStatus::Active && $this->banned_at === null;
    }

    public function hasRole(UserRole ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function specialistProfile(): HasOne
    {
        return $this->hasOne(SpecialistProfile::class);
    }

    public function pharmacyStaffProfile(): HasOne
    {
        return $this->hasOne(PharmacyStaffProfile::class);
    }

    public function deliveryAgentProfile(): HasOne
    {
        return $this->hasOne(DeliveryAgentProfile::class);
    }

    public function labEmployeeProfile(): HasOne
    {
        return $this->hasOne(LabEmployeeProfile::class);
    }
}

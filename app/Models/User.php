<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nin',
        'role',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function homeRouteName(): string
    {
        return $this->isAdmin() ? 'admin.dashboard' : 'admin.suppliers.index';
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class, 'created_by_user_id');
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'created_by_user_id');
    }

    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class, 'created_by_user_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(CustomerBooking::class, 'created_by_user_id', 'id');
    }

    public function complianceNotificationLogs(): HasMany
    {
        return $this->hasMany(ComplianceNotificationLog::class);
    }
}

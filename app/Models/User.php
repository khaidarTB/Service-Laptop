<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function assignedServices(): HasMany
    {
        return $this->hasMany(Service::class, 'assigned_technician_id');
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(ServiceStatusLog::class, 'changed_by');
    }

    public function customerProfile()
    {
        return $this->hasOne(Customer::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeknisi(): bool
    {
        return $this->role === 'teknisi';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Order;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',        // include role
    ];

    /**
     * Hidden attributes
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_confirmed_at' => 'datetime',
    ];

    /**
     * Boot method for auto-generating IDs
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {

            // DRIVER ID
            if ($user->role === 'driver') {
                $lastId = User::where('role', 'driver')->max('id') + 1;
                $randomLetters = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2));
                $user->driver_id = 'DRV-' . str_pad($lastId, 2, '0', STR_PAD_LEFT) . '-' . $randomLetters;
            }

            // CLIENT ID (collision-proof)
            if ($user->role === 'client') {
                do {
                    $randomId = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4));
                    $clientId = 'CST-' . $randomId;
                } while (User::where('client_id', $clientId)->exists());

                $user->client_id = $clientId;
            }
        });
    }

    /**
     * Relationships
     */
    public function clientOrders()
    {
        return $this->hasMany(Order::class, 'client_id');
    }

    public function driverOrders()
    {
        return $this->hasMany(Order::class, 'driver_id');
    }

    /**
     * @return HasMany<Order>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isDriver(): bool
    {
        return $this->role === 'driver';
    }
    public function isClient(): bool
    {
        return $this->role === 'client';
    }
}
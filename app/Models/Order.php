<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'driver_id',
        'pickup_address',
        'destination_address',
        'price',
        'status',
        'tracking_number',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            do {
                $randomSuffix = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3));
                $trackingNumber = 'ORD-' . now()->format('YmdHi') . '-' . $randomSuffix;
            } while (Order::where('tracking_number', $trackingNumber)->exists());

            $order->tracking_number = $trackingNumber;
        });
    }

    // Relationships
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}

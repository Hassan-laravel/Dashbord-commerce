<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Relationship with order items
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relationship with the customer (User)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Boot function to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            // Generate order number automatically, e.g., ORD-20260221-A1B2
            $order->number = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        });
    }
}

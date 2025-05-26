<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'user_id',
        'awb',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function shipping()
    {
        return $this->hasOne(Shipping::class);
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
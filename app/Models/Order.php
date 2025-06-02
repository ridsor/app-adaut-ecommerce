<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Searchable;

class Order extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'amount',
        'user_id',
        'awb',
        'status',
        'note'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = 'ORD' . Str::upper(Str::random(10));;
        });
    }

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

    public function review()
    {
        return $this->hasMany(Review::class);
    }

    #[SearchUsingFullText(['order_number'])]
    public function toSearchableArray(): array
    {
        return [
            'order_number' => $this->order_number,
        ];
    }
}

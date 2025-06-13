<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Searchable;

class Order extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'amount',
        'user_id',
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

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getTotalPriceAttribute()
    {
        return $this->order_items->sum(function ($item) {
            return $item->quantity * optional($item->product)->price;
        });
    }

    #[SearchUsingFullText(['order_number'])]
    public function toSearchableArray(): array
    {
        return [
            'order_number' => $this->order_number,
        ];
    }

    static public function scopeFilters($query, array $filters)
    {
        // sort
        $query->when($filters['sort'] ?? 'latest', function ($query, $sort) {
            if ($sort == 'oldest') {
                $query->oldest();
            } else if ($sort == "lowest_price") {
                $query->orderBy('amount', 'asc');
            } else if ($sort == "highest_price") {
                $query->orderBy('amount', 'desc');
            } else {
                $query->latest();
            }
        });

        // status
        $query->when($filters['status'] ?? false, function ($query, $status) {
            $query->where('status', $status);
        });

        // courir
        $query->when($filters['courir'] ?? false, function ($query, $courir) {
            $query->whereHas('shipping', function ($query) use ($courir) {
                $query->whereIn('name', $courir);
            });
        });
    }
}

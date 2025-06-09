<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'rating',
        'comment',
        'image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function review_media()
    {
        return $this->hasMany(ReviewMedia::class);
    }

    public function scopeFilter($query, array $filter)
    {
        $query->when($filter['rating'] ?? false, function ($query, $rating) {
            $query->where('rating', $rating);
        });
    }
}

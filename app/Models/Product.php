<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Exception;

class Product extends Model
{
    use HasFactory, Searchable;

    protected $filltable = [
        'name',
        'image',
        'price',
        'description',
        'stock',
        'category_id'
    ];

    public function order_items()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function decreaseStock($quantity)
    {
        if ($this->stock < $quantity) {
            throw new Exception("Stok tidak cukup untuk produk: {$this->name}");
        }

        $this->stock -= $quantity;
        $this->save();
    }

    static public function filters($query, array $filters)
    {
        $query->select(['id', 'name', 'image'])->withCount('reviews')->withAvg('reviews', 'rating');

        // sort "latest, oldest, bestsellers, highest_price, lowest_price"
        // dd($query->first());
        $query->when($filters['sort'] ?? false, function ($query, $sort) {
            if ($sort === 'oldest') {
                $query->oldest();
            } elseif ($sort === 'bestsellers') {
                $query->withSum([
                    'orders as total_sold'
                ], 'quantity')
                    ->orderByDesc('total_sold');
            } elseif ($sort === 'highest_price') {
                $query->orderByDesc('price');
            } elseif ($sort === 'lowest_price') {
                $query->orderBy('price');
            } else {
                $query->latest();
            }
        });

        // category
        $query->when($filters['category'] ?? false, function ($query, $category) {
            $query->whereHas("category", function ($query) use ($category) {
                $query->where('name', $category);
            });
        });

        // price
        $query->when($filters['max_price'] ?? false, fn($query, $price) => $query->where('price', '<=', $price));
        $query->when($filters['min_price'] ?? false, fn($query, $price) => $query->where('price', '>=', $price));

        // min age
        $query->when($filters['stock'] ?? false, fn($query, $stock) => $query->where('stock', '>=', (int)$stock));

        return $query;
    }

    #[SearchUsingFullText(['name'])]
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}

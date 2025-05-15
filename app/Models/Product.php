<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Exception;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'name',
        'image',
        'price',
        'description',
        'stock',
        'category_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
            
            // Jika slug sudah ada, tambahkan ID atau angka acak
            $originalSlug = $product->slug;
            $count = 1;
            
            while (static::where('slug', $product->slug)->exists()) {
                $product->slug = $originalSlug . '-' . $count++;
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) { // Cek jika `name` berubah
                $product->slug = Str::slug($product->name);
                
                $originalSlug = $product->slug;
                $count = 1;
                
                while (static::where('slug', $product->slug)->where('id', '!=', $product->id)->exists()) {
                    $product->slug = $originalSlug . '-' . $count++;
                }
            }
        });
    }

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
        return $this->belongsTo(Category::class, 'category_id');
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
        $query->select(['id', 'name', 'image', 'slug'])->withCount('reviews')->withAvg('reviews', 'rating');

        // sort "latest, oldest, bestsellers, highest_price, lowest_price"
        $query->when($filters['sort'] ?? false, function ($query, $sort) {
            if ($sort === 'oldest') {
                $query->oldest();
            } elseif ($sort === 'bestsellers') {
                $query->withSum([
                    'order_items as total_sold'
                ], 'quantity')
                ->orderBy('total_sold', 'desc')
                ->orderBy('reviews_avg_rating', 'desc');
            } elseif ($sort === 'highest_price') {
                $query->orderBy('price', 'desc');
            } elseif ($sort === 'lowest_price') {
                $query->orderBy('price', 'asc');
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
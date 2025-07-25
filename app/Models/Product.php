<?php

namespace App\Models;

use App\Exceptions\CustomException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\ProductTrait;

class Product extends Model
{
    use HasFactory, Searchable, SoftDeletes, ProductTrait;

    protected $fillable = [
        'name',
        'image',
        'price',
        'description',
        'stock',
        'weight',
        'category_id'
    ];

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->sku = self::generateSku($product);
            $product->slug = Str::slug($product->name);

            // Jika slug sudah ada, tambahkan ID atau angka acak
            $originalSlug = $product->slug;
            $count = 1;

            while (static::where('slug', $product->slug)->exists()) {
                $product->slug = $originalSlug . '-' . $count++;
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($product->name);

                $originalSlug = $product->slug;
                $count = 1;

                while (static::where('slug', $product->slug)->where('id', '!=', $product->id)->exists()) {
                    $product->slug = $originalSlug . '-' . $count++;
                }
            }

            if ($product->isDirty('category_id') || $product->isDirty('name')) {
                $product->sku = self::generateSku($product);
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
            throw new CustomException("Stok produk yang dipilih tidak mencukupi.");
        }

        $this->stock -= $quantity;
        $this->save();
    }

    static public function scopeFilters($query, array $filters)
    {
        $query->when($filters['sort'] ?? 'latest', function ($query, $sort) {
            if ($sort == 'oldest') {
                $query->oldest();
            } elseif ($sort == 'bestsellers') {
                $query->withSum([
                    'order_items as total_sold'
                ], 'quantity')
                    ->orderBy('total_sold', 'desc');
            } elseif ($sort == 'asc') {
                $query->orderBy('name', 'asc');
            } elseif ($sort == 'desc') {
                $query->orderBy('name', 'desc');
            } elseif ($sort == 'highest_price') {
                $query->orderBy('price', 'desc');
            } elseif ($sort == 'lowest_price') {
                $query->orderBy('price', 'asc');
            } elseif ($sort == 'review') {
                $query->orderBy('reviews_avg_rating', 'desc');
            } else {
                $query->latest();
            }
        });

        // categories
        $query->when($filters['categories'] ?? false, function ($query, $categories) {
            $query->whereHas("category", function ($query) use ($categories) {
                $query->whereIn('slug', $categories);
            });
        });

        // rating
        $query->when($filters['rating'] ?? false, function ($query, $rating) {
            $query->whereExists(function ($query) use ($rating) {
                $query->selectRaw('product_id, AVG(rating) as avg_rating')
                    ->from('reviews')
                    ->whereColumn('products.id', 'reviews.product_id')
                    ->groupBy('product_id')
                    ->havingRaw('FLOOR(avg_rating) IN (' . implode(',', $rating) . ')');
            });
        });

        // price
        $query->when($filters['max_price'] ?? false, fn($query, $price) => $query->where('price', '<=', $price));
        $query->when($filters['min_price'] ?? false, fn($query, $price) => $query->where('price', '>=', $price));

        // availability
        $query->when($filters['availability'] ?? false, function ($query, $availability) {
            if ($availability == 'stock') {
                $query->where('stock', '>', 0);
            }
        });

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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'name',
        'icon',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);

            // Jika slug sudah ada, tambahkan ID atau angka acak
            $originalSlug = $category->slug;
            $count = 1;

            while (static::where('slug', $category->slug)->exists()) {
                $category->slug = $originalSlug . '-' . $count++;
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) { // Cek jika `name` berubah
                $category->slug = Str::slug($category->name);

                $originalSlug = $category->slug;
                $count = 1;

                while (static::where('slug', $category->slug)->where('id', '!=', $category->id)->exists()) {
                    $category->slug = $originalSlug . '-' . $count++;
                }
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    #[SearchUsingFullText(['name'])]
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
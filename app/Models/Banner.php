<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Laravel\Scout\Attributes\SearchUsingFullText;

class Banner extends Model
{
    use Searchable;

    protected $fillable = [
        'title',
        'description',
        'image',
        'button_text',
        'button_link',
    ];

    #[SearchUsingFullText(['title'])]
    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewMedia extends Model
{
    protected $table = 'review_media';
    public $timestamps = false;
    protected $fillable = [
        'file_path',
        'type'
    ];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}

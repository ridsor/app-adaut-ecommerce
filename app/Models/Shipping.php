<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'cost',
        'etd',
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
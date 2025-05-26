<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'payment_method',
        'status',
        'amount',
        'payment_date'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
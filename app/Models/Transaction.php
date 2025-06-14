<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'url',
        'invoice',
        'expired_at'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}

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
        'recipient_name',
        'address',
        'note',
        'phone_number',
        'province_name',
        'city_name',
        'district_name',
        'subdistrict_name',
        'zip_code',
        'address_label',
        'destination_id',
    ];

    public $timestamps = false;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

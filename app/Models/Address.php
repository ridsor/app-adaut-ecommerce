<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'address';
    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'province_name',
        'city_name',
        'district_name',
        'subdistrict_name',
        'zip_code',
        'address_label',
        'destination_id',
        'note'
    ];

    public function user()
    {
        return $this->belongsTo('user', User::class);
    }
}
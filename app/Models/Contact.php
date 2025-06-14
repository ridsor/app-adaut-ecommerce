<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'email',
        'name',
        'subject',
        'description',
        'status',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->when($search ?? false, function ($query) use ($search) {
            $query->whereFullText(['name', 'email', 'subject'], $search);
        });
    }

    public function scopeFilter($query, array $filters)
    {
        // sort
        $query->when($filters['sort'] ?? 'latest', function ($query, $sort) {
            if ($sort == 'oldest') {
                $query->oldest();
            } else {
                $query->latest();
            }
        });

        // status
        $query->when($filters['status'] ?? false, function ($query, $status) {
            $query->where('status', $status);
        });
    }
}

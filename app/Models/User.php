<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, CanResetPassword, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_verified_at'
    ];
    protected $with = ['profile'];
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function address()
    {
        return $this->hasOne(Address::class);
    }
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function isUser()
    {
        return $this->role === 'user';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->username = Str::slug($user->name, "_");

            $originalusername = $user->username;
            $count = 1;

            while (static::where('username', $user->username)->where('id', '!=', $user->id)->exists()) {
                $user->username = $originalusername . '_' . $count++;
            }
        });

        static::updating(function ($user) {
            if ($user->isDirty('name')) {
                $user->username = Str::slug($user->name, "_");

                $originalusername = $user->username;
                $count = 1;

                while (static::where('username', $user->username)->where('id', '!=', $user->id)->exists()) {
                    $user->username = $originalusername . '_' . $count++;
                }
            }
        });
    }
}
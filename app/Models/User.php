<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'phone_no',
        'is_member',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'password' => 'hashed',
        'is_member' => 'boolean',
    ];
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id', 'id');
    }
}

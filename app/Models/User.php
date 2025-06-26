<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';
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
        'is_member' => 'boolean',
        // 'email_verified_at' =>
        'password' => 'hashed',
    ];
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}

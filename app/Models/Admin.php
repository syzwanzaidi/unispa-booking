<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    protected $primaryKey = 'admin_id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'admin_username',
        'admin_password',
    ];
    protected $hidden = [
        'admin_password',
    ];
    protected $casts = [
        'admin_password' => 'hashed',
    ];
}

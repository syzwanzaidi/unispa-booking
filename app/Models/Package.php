<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;
    protected $primaryKey = 'package_id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'package_name',
        'package_desc',
        'package_price',
        'duration',
    ];
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'package_id', 'package_id');
    }
}

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
        'category',
        'package_desc',
        'package_price',
        'duration',
        'capacity',
    ];

    protected $casts = [
        'package_price' => 'decimal:2',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'package_id', 'package_id');
    }
}

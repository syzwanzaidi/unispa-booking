<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;
    protected $primaryKey = 'booking_id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'booking_pax',
        'booking_time',
        'booking_date',
        'payment_method',
        'package_id',
        'user_id',
    ];
    protected $casts = [
        'booking_time' => 'datetime',
        'booking_date' => 'date',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id', 'package_id');
    }
    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'booking_id', 'booking_id');
    }
}

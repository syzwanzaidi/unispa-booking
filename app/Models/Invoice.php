<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    use HasFactory;
    protected $primaryKey = 'invoice_id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'booking_id',
        'total_price',
        'generated_at',
    ];
    protected $casts = [
        'generated_at' => 'datetime',
        'total_price' => 'decimal:2',
    ];
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'invoice_id', 'invoice_id');
    }
}

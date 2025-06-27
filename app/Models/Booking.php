<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;
    protected $primaryKey = 'booking_id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'user_id',
        'booking_date',
        'booking_status',
        'payment_method',
        'total_amount',
        'notes',
    ];
    protected $casts = [
        'booking_date' => 'date',
    ];
    public function user(): BelongsTo { return $this->belongsTo(User::class, 'user_id', 'id'); }
    public function bookingItems(): HasMany { return $this->hasMany(BookingItem::class, 'booking_id', 'booking_id'); }
    public function invoice(): HasOne { return $this->hasOne(Invoice::class, 'booking_id', 'booking_id'); }
}

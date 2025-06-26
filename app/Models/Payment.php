<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;
    protected $primaryKey = 'payment_id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'invoice_id',
        'payment_method',
        'payment_status',
        'payment_date',
    ];
    protected $casts = [
        'payment_date' => 'date',
    ];
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }
}

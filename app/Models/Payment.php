<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'customer_id',
        'customer_name',
        'customer_email',
        'amount',
        'usd_amount',
        'usd_amount',
        'currency',
        'reference_no',
        'payment_date',
        'invoice_id',
        'is_processed',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'created_at' => 'date',
        'is_processed' => 'boolean',
        'amount' => 'decimal:2',
        'usd_amount' => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}

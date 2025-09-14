<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'customer_name',
        'customer_email',
        'amount',
        'is_sent',
    ];

    protected $casts = [
        'is_sent' => 'boolean',
        'amount' => 'decimal:2',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}

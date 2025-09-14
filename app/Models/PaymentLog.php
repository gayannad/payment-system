<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $fillable = [
        'file_name',
        'row_number',
        'reference',
        'status',
        'message',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}

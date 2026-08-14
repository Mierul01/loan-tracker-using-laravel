<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'balance',
        'document',
        'note',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'float',
        'balance' => 'float',
        'paid_at' => 'date',
    ];
}

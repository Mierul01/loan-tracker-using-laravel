<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanSetting extends Model
{
    protected $fillable = [
        'borrower_name',
        'loan_title',
        'total_loan',
        'start_date',
        'notes',
    ];

    protected $casts = [
        'total_loan' => 'float',
        'start_date' => 'date',
    ];

    public static function current()
    {
        return static::query()->first() ?? static::create([
            'borrower_name' => 'Borrower',
            'loan_title' => 'Personal Loan',
            'total_loan' => 8000,
            'start_date' => now()->toDateString(),
        ]);
    }
}

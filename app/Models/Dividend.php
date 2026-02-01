<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dividend extends Model
{
    protected $fillable = [
        'fiscal_year_id', 'member_id', 'share_amount', 
        'dividend_rate', 'dividend_amount', 'is_paid', 'paid_date'
    ];

    protected $casts = [
        'share_amount' => 'decimal:2',
        'dividend_rate' => 'decimal:2',
        'dividend_amount' => 'decimal:2',
        'is_paid' => 'boolean',
        'paid_date' => 'date',
    ];

    public function fiscalYear(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}

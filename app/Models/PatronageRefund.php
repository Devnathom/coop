<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatronageRefund extends Model
{
    protected $fillable = [
        'fiscal_year_id', 'member_id', 'purchase_amount', 
        'refund_rate', 'refund_amount', 'is_paid', 'paid_date'
    ];

    protected $casts = [
        'purchase_amount' => 'decimal:2',
        'refund_rate' => 'decimal:2',
        'refund_amount' => 'decimal:2',
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

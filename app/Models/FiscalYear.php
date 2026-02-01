<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FiscalYear extends Model
{
    protected $fillable = [
        'name', 'start_date', 'end_date', 'total_sales', 
        'total_profit', 'dividend_rate', 'patronage_refund_rate', 'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_sales' => 'decimal:2',
        'total_profit' => 'decimal:2',
        'dividend_rate' => 'decimal:2',
        'patronage_refund_rate' => 'decimal:2',
    ];

    public function dividends(): HasMany
    {
        return $this->hasMany(Dividend::class);
    }

    public function patronageRefunds(): HasMany
    {
        return $this->hasMany(PatronageRefund::class);
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'open' => 'เปิด',
            'closed' => 'ปิด',
            'calculated' => 'คำนวณแล้ว',
            default => $this->status,
        };
    }
}

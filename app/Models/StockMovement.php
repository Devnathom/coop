<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id', 'user_id', 'type', 'quantity', 
        'stock_before', 'stock_after', 'cost_price', 'reference', 'note'
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeTextAttribute(): string
    {
        return match($this->type) {
            'in' => 'รับเข้า',
            'out' => 'จ่ายออก',
            'adjust' => 'ปรับปรุง',
            default => $this->type,
        };
    }
}

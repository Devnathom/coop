<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'invoice_number', 'member_id', 'user_id', 'subtotal', 
        'discount', 'total', 'paid_amount', 'change_amount', 'payment_method', 'note'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public static function generateInvoiceNumber(): string
    {
        $today = now()->format('Ymd');
        $lastSale = self::whereDate('created_at', today())->orderBy('id', 'desc')->first();
        $sequence = $lastSale ? intval(substr($lastSale->invoice_number, -4)) + 1 : 1;
        return 'INV' . $today . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}

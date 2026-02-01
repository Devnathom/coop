<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'code', 'name', 'barcode', 'category_id', 'cost_price', 
        'selling_price', 'stock_quantity', 'min_stock', 'unit', 'image', 'is_active'
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->min_stock;
    }

    public static function generateCode(): string
    {
        $lastProduct = self::orderBy('id', 'desc')->first();
        $nextNumber = $lastProduct ? intval(substr($lastProduct->code, 1)) + 1 : 1;
        return 'P' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}

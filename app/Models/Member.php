<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    protected $fillable = [
        'member_code', 'name', 'id_card', 'phone', 'email', 
        'address', 'join_date', 'share_amount', 'accumulated_purchase', 'is_active'
    ];

    protected $casts = [
        'join_date' => 'date',
        'share_amount' => 'decimal:2',
        'accumulated_purchase' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function dividends(): HasMany
    {
        return $this->hasMany(Dividend::class);
    }

    public function patronageRefunds(): HasMany
    {
        return $this->hasMany(PatronageRefund::class);
    }

    public static function generateMemberCode(): string
    {
        $lastMember = self::orderBy('id', 'desc')->first();
        $nextNumber = $lastMember ? intval(substr($lastMember->member_code, 1)) + 1 : 1;
        return 'M' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}

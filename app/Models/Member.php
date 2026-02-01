<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    protected $fillable = [
        'member_code', 'name', 'class_level', 'room', 'email', 
        'join_date', 'share_amount', 'accumulated_purchase', 'is_active'
    ];

    // ระดับชั้นทั้งหมด
    public static $classLevels = [
        'อ.1' => 'อนุบาล 1',
        'อ.2' => 'อนุบาล 2',
        'อ.3' => 'อนุบาล 3',
        'ป.1' => 'ประถมศึกษาปีที่ 1',
        'ป.2' => 'ประถมศึกษาปีที่ 2',
        'ป.3' => 'ประถมศึกษาปีที่ 3',
        'ป.4' => 'ประถมศึกษาปีที่ 4',
        'ป.5' => 'ประถมศึกษาปีที่ 5',
        'ป.6' => 'ประถมศึกษาปีที่ 6',
        'ม.1' => 'มัธยมศึกษาปีที่ 1',
        'ม.2' => 'มัธยมศึกษาปีที่ 2',
        'ม.3' => 'มัธยมศึกษาปีที่ 3',
        'ม.4' => 'มัธยมศึกษาปีที่ 4',
        'ม.5' => 'มัธยมศึกษาปีที่ 5',
        'ม.6' => 'มัธยมศึกษาปีที่ 6',
        'ครู' => 'ครู/บุคลากร',
    ];

    // ลำดับชั้นสำหรับเลื่อนชั้น
    public static $classOrder = [
        'อ.1', 'อ.2', 'อ.3', 'ป.1', 'ป.2', 'ป.3', 'ป.4', 'ป.5', 'ป.6',
        'ม.1', 'ม.2', 'ม.3', 'ม.4', 'ม.5', 'ม.6'
    ];

    // เลื่อนชั้นเรียน
    public function promoteClass(): bool
    {
        $currentIndex = array_search($this->class_level, self::$classOrder);
        if ($currentIndex === false || $currentIndex >= count(self::$classOrder) - 1) {
            return false; // ไม่สามารถเลื่อนได้ (ม.6 หรือ ครู)
        }
        $this->class_level = self::$classOrder[$currentIndex + 1];
        return $this->save();
    }

    // ชื่อระดับชั้นเต็ม
    public function getClassNameAttribute(): string
    {
        return self::$classLevels[$this->class_level] ?? $this->class_level ?? '-';
    }

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

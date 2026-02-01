<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'ผู้ดูแลระบบ',
            'email' => 'admin@schoolcoop.com',
            'password' => Hash::make('password'),
        ]);

        // Create Categories
        $categories = [
            ['name' => 'เครื่องเขียน', 'description' => 'ปากกา ดินสอ ยางลบ ไม้บรรทัด'],
            ['name' => 'สมุด/กระดาษ', 'description' => 'สมุดบันทึก สมุดวาดเขียน กระดาษ A4'],
            ['name' => 'อุปกรณ์การเรียน', 'description' => 'กระเป๋านักเรียน ขวดน้ำ กล่องดินสอ'],
            ['name' => 'ขนม', 'description' => 'ขนมขบเคี้ยว ลูกอม บิสกิต'],
            ['name' => 'เครื่องดื่ม', 'description' => 'น้ำดื่ม นม น้ำผลไม้'],
            ['name' => 'อาหาร', 'description' => 'บะหมี่กึ่งสำเร็จรูป ขนมปัง'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Create Sample Products
        $products = [
            ['name' => 'ปากกาลูกลื่น', 'category_id' => 1, 'cost_price' => 5, 'selling_price' => 8, 'stock_quantity' => 100, 'min_stock' => 20, 'unit' => 'ด้าม'],
            ['name' => 'ดินสอ 2B', 'category_id' => 1, 'cost_price' => 3, 'selling_price' => 5, 'stock_quantity' => 150, 'min_stock' => 30, 'unit' => 'แท่ง'],
            ['name' => 'ยางลบ', 'category_id' => 1, 'cost_price' => 2, 'selling_price' => 5, 'stock_quantity' => 80, 'min_stock' => 20, 'unit' => 'ก้อน'],
            ['name' => 'ไม้บรรทัด 30 ซม.', 'category_id' => 1, 'cost_price' => 8, 'selling_price' => 12, 'stock_quantity' => 50, 'min_stock' => 10, 'unit' => 'อัน'],
            ['name' => 'สมุดบันทึก 70 แผ่น', 'category_id' => 2, 'cost_price' => 12, 'selling_price' => 18, 'stock_quantity' => 60, 'min_stock' => 15, 'unit' => 'เล่ม'],
            ['name' => 'สมุดวาดเขียน A4', 'category_id' => 2, 'cost_price' => 25, 'selling_price' => 35, 'stock_quantity' => 40, 'min_stock' => 10, 'unit' => 'เล่ม'],
            ['name' => 'กระดาษ A4 (แพ็ค 100)', 'category_id' => 2, 'cost_price' => 45, 'selling_price' => 60, 'stock_quantity' => 30, 'min_stock' => 5, 'unit' => 'แพ็ค'],
            ['name' => 'กล่องดินสอ', 'category_id' => 3, 'cost_price' => 20, 'selling_price' => 35, 'stock_quantity' => 25, 'min_stock' => 5, 'unit' => 'ใบ'],
            ['name' => 'ขวดน้ำ 500ml', 'category_id' => 3, 'cost_price' => 35, 'selling_price' => 55, 'stock_quantity' => 20, 'min_stock' => 5, 'unit' => 'ใบ'],
            ['name' => 'ขนมปังกรอบ', 'category_id' => 4, 'cost_price' => 8, 'selling_price' => 10, 'stock_quantity' => 50, 'min_stock' => 15, 'unit' => 'ซอง'],
            ['name' => 'ลูกอมผลไม้', 'category_id' => 4, 'cost_price' => 5, 'selling_price' => 7, 'stock_quantity' => 80, 'min_stock' => 20, 'unit' => 'ซอง'],
            ['name' => 'น้ำดื่ม 600ml', 'category_id' => 5, 'cost_price' => 5, 'selling_price' => 7, 'stock_quantity' => 100, 'min_stock' => 30, 'unit' => 'ขวด'],
            ['name' => 'นมกล่อง UHT', 'category_id' => 5, 'cost_price' => 10, 'selling_price' => 14, 'stock_quantity' => 48, 'min_stock' => 12, 'unit' => 'กล่อง'],
            ['name' => 'น้ำผลไม้กล่อง', 'category_id' => 5, 'cost_price' => 12, 'selling_price' => 15, 'stock_quantity' => 36, 'min_stock' => 12, 'unit' => 'กล่อง'],
            ['name' => 'บะหมี่กึ่งสำเร็จรูป', 'category_id' => 6, 'cost_price' => 6, 'selling_price' => 8, 'stock_quantity' => 60, 'min_stock' => 20, 'unit' => 'ซอง'],
        ];

        $code = 1;
        foreach ($products as $prod) {
            Product::create(array_merge($prod, ['code' => 'P' . str_pad($code++, 5, '0', STR_PAD_LEFT)]));
        }

        // Create Sample Members
        $members = [
            ['name' => 'ด.ช.สมชาย ใจดี', 'phone' => '0812345678', 'share_amount' => 500],
            ['name' => 'ด.ญ.สมหญิง รักเรียน', 'phone' => '0823456789', 'share_amount' => 300],
            ['name' => 'ด.ช.ภูมิ พัฒนา', 'phone' => '0834567890', 'share_amount' => 200],
            ['name' => 'ด.ญ.พิมพ์ ใจสะอาด', 'phone' => '0845678901', 'share_amount' => 400],
            ['name' => 'ด.ช.ธนา รุ่งเรือง', 'phone' => '0856789012', 'share_amount' => 600],
        ];

        $memberCode = 1;
        foreach ($members as $mem) {
            Member::create(array_merge($mem, [
                'member_code' => 'M' . str_pad($memberCode++, 5, '0', STR_PAD_LEFT),
                'join_date' => now(),
            ]));
        }
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('barcode')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->decimal('cost_price', 12, 2);
            $table->decimal('selling_price', 12, 2);
            $table->integer('stock_quantity')->default(0);
            $table->integer('min_stock')->default(5);
            $table->string('unit')->default('ชิ้น');
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

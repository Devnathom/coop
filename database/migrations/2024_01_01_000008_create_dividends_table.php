<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dividends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fiscal_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->decimal('share_amount', 12, 2);
            $table->decimal('dividend_rate', 5, 2);
            $table->decimal('dividend_amount', 12, 2);
            $table->boolean('is_paid')->default(false);
            $table->date('paid_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dividends');
    }
};

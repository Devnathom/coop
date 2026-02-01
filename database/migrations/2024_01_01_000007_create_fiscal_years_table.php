<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiscal_years', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_sales', 15, 2)->default(0);
            $table->decimal('total_profit', 15, 2)->default(0);
            $table->decimal('dividend_rate', 5, 2)->default(0);
            $table->decimal('patronage_refund_rate', 5, 2)->default(0);
            $table->enum('status', ['open', 'closed', 'calculated'])->default('open');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fiscal_years');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            // Remove old fields
            $table->dropColumn(['id_card', 'phone', 'address']);
            
            // Add new fields
            $table->string('class_level')->nullable()->after('name'); // ระดับชั้น เช่น ป.1, ป.2, ม.1
            $table->string('room')->nullable()->after('class_level'); // ห้อง เช่น 1, 2, 3
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['class_level', 'room']);
            
            $table->string('id_card', 13)->nullable()->unique();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
        });
    }
};

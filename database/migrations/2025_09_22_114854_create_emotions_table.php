<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // กันสร้างซ้ำ ถ้ามีอยู่แล้ว
        if (Schema::hasTable('emotions')) {
            return;
        }

        Schema::create('emotions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();      // ชื่ออารมณ์ (ไม่ซ้ำ)
            $table->text('description')->nullable(); // รายละเอียดเพิ่มเติม (ไม่บังคับ)
            $table->timestamps();                  // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emotions');
    }
};

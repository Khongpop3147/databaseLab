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
        Schema::create('diary_entries', function (Blueprint $table) {
            $table->id();

            // เชื่อมกับ users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // วันที่ของบันทึก (เก็บเฉพาะวัน)
            $table->date('date');

            // เนื้อหาของบันทึก
            $table->text('content');

            // ถ้าต้องการ title ให้เพิ่ม: $table->string('title')->nullable();
            // ถ้าต้องการ public/private หรือ mood เพิ่มฟิลด์อื่น ๆ ตามต้องการ

            $table->timestamps();

            // ถ้าต้องการ soft deletes ให้เอาคอมเมนต์ออก:
            // $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diary_entries');
    }
};

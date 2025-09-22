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
        // กันสร้างซ้ำถ้ามีอยู่แล้ว
        if (Schema::hasTable('diary_entry_emotions')) {
            return;
        }

        Schema::create('diary_entry_emotions', function (Blueprint $table) {
            $table->id();

            // FK ไปยัง diary_entries / emotions (ลบ parent แล้วลบ pivot ตาม)
            $table->foreignId('diary_entry_id')
                  ->constrained('diary_entries')
                  ->cascadeOnDelete();

            $table->foreignId('emotion_id')
                  ->constrained('emotions')
                  ->cascadeOnDelete();

            // ค่าความเข้ม 1–10 (ให้เป็น nullable เผื่อไม่ได้กรอก)
            $table->unsignedTinyInteger('intensity')->nullable();

            $table->timestamps();

            // ไม่ให้ซ้ำคู่กันในหนึ่งรายการ
            $table->unique(['diary_entry_id', 'emotion_id'], 'dee_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diary_entry_emotions');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();

            // เจ้าของ reminder
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // เนื้อหา reminder
            $table->string('title');          // หัวข้อสั้นๆ
            $table->text('notes')->nullable(); // รายละเอียดเพิ่มเติม (ถ้ามี)

            // เวลาเตือน (ถ้าเป็นเพียง to-do และยังไม่กำหนดเวลาจริง ให้ปล่อยได้)
            $table->dateTime('remind_at')->nullable();

            // สถานะ (เลือกอย่างใดอย่างหนึ่ง: new, done, canceled)
            $table->string('status')->default('new');

            $table->timestamps();

            // ⚡ indexes ที่ใช้บ่อย
            $table->index(['user_id', 'remind_at']);
            $table->index(['user_id', 'status']);

            // (ทางเลือก) กันรายการซ้ำภายในผู้ใช้คนเดียวกันเวลาเดียวกัน
            // $table->unique(['user_id', 'title', 'remind_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};


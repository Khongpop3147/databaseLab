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
        // กันเคสที่ตารางมีอยู่แล้ว จะไม่พยายามสร้างซ้ำ
        if (Schema::hasTable('diary_entries')) {
            return;
        }

        Schema::create('diary_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->date('date');
            $table->text('content');
            $table->timestamps();

            // ไม่ให้ user เดียวมีบันทึกชนวันเดียวกัน
            $table->unique(['user_id', 'date'], 'diary_entries_user_date_unique');
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

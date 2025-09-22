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
        if (Schema::hasTable('taggables')) {
            return;
        }

        Schema::create('taggables', function (Blueprint $table) {
            // แท็กที่อ้างถึง (ลบแท็กแล้วลบความสัมพันธ์ตาม)
            $table->foreignId('tag_id')
                  ->constrained('tags')
                  ->cascadeOnDelete();

            // polymorphic keys
            $table->unsignedBigInteger('taggable_id');
            $table->string('taggable_type');

            $table->timestamps();

            // ช่วย query เร็วขึ้นเวลาโหลดความสัมพันธ์
            $table->index(['taggable_type', 'taggable_id'], 'taggable_type_id_index');

            // ไม่ให้ซ้ำคู่กัน (tag เดิมผูกกับเรคอร์ดเดิมซ้ำ)
            $table->unique(['tag_id', 'taggable_type', 'taggable_id'], 'taggable_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taggables');
    }
};

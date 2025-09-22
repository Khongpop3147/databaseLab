<?php

namespace Database\Factories;

use App\Models\DiaryEntry;            // ✅ อ้างโมเดลให้ถูก
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<\App\Models\DiaryEntry> */
class DiaryEntryFactory extends Factory
{
    protected $model = DiaryEntry::class; // ✅ ผูกกับโมเดล

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),                                   // ผูกกับผู้ใช้
            'date'    => $this->faker->dateTimeBetween('-60 days','today')->format('Y-m-d'),
            'content' => $this->faker->paragraphs(2, true),
        ];
    }
}

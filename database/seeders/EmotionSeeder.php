<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Emotion;

class EmotionSeeder extends Seeder
{
    public function run(): void
    {
        $list = [
            ['name' => 'Happy',   'description' => 'Feeling of joy or pleasure'],
            ['name' => 'Sad',     'description' => 'Feeling of sorrow or unhappiness'],
            ['name' => 'Angry',   'description' => 'Strong displeasure or hostility'],
            ['name' => 'Excited', 'description' => 'Enthusiasm and eagerness'],
            ['name' => 'Anxious', 'description' => 'Worry or unease'],
        ];

        foreach ($list as $item) {
            Emotion::firstOrCreate(
                ['name' => $item['name']],
                ['description' => $item['description'] ?? null]
            );
        }
    }
}

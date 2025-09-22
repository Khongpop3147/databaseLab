<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'work',
            'health',
            'idea',
            'mood',
            'travel',
            'study',
            'family',
        ];

        foreach ($names as $name) {
            Tag::firstOrCreate(['name' => $name]);
        }
    }
}

<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DiaryEntry;

class DiaryEntrySeeder extends Seeder
{
    public function run(): void
    {
        // สร้าง 3–5 entries ต่อผู้ใช้ โดยเลี่ยงชน unique (user_id, date)
        User::all()->each(function ($user) {
            // เก็บวันที่ที่ใช้แล้วต่อ user เพื่อลดโอกาสชน
            $used = [];
            foreach (range(1, rand(3,5)) as $_) {
                // สุ่มวันที่จนกว่าจะไม่ซ้ำใน array นี้
                do {
                    $date = fake()->dateTimeBetween('-60 days', 'today')->format('Y-m-d');
                } while (in_array($date, $used));
                $used[] = $date;

                DiaryEntry::updateOrCreate(
                    ['user_id' => $user->id, 'date' => $date], // ปลอดภัยต่อการรันซ้ำ
                    ['content' => fake()->paragraphs(2, true)]
                );
            }
        });
    }
}

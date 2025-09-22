<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\DiaryEntry;
use App\Models\Emotion;
use App\Models\Tag;
use App\Models\Reminder; // ← (1) เพิ่ม use Reminder

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // (A) แอดมิน (idempotent ด้วย updateOrCreate)
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'              => 'Admin',
                'password'          => Hash::make('password'), // TODO: เปลี่ยนภายหลัง
                'email_verified_at' => now(),
            ]
        );

        // (B) ผู้ใช้ตัวอย่าง
        $users = User::factory()->count(5)->create();
        $users->push($admin); // รวมแอดมินให้มี entries ด้วย

        // (C) ไดอารี่ของผู้ใช้แต่ละคน (3–5 รายการ) — กันชน (user_id,date)
        foreach ($users as $user) {
            $usedDates = [];
            foreach (range(1, rand(3, 5)) as $i) {
                do {
                    $date = fake()->dateTimeBetween('-60 days', 'today')->format('Y-m-d');
                } while (in_array($date, $usedDates, true));
                $usedDates[] = $date;

                DiaryEntry::updateOrCreate(
                    ['user_id' => $user->id, 'date' => $date],
                    ['content' => fake()->paragraphs(2, true)]
                );
            }
        }

        // (D) Seed Emotion พื้นฐาน
        $this->call(EmotionSeeder::class);

        // (E) แนบ Emotion ให้แต่ละ DiaryEntry แบบสุ่ม (0–3 อัน) + intensity 1–10 (idempotent)
        $emotionIds = Emotion::pluck('id')->all();
        if (!empty($emotionIds)) {
            DiaryEntry::query()->each(function (DiaryEntry $entry) use ($emotionIds) {
                if ($entry->emotions()->count() > 0) {
                    return; // มีแล้ว ข้าม
                }
                $pickCount = rand(0, 3);
                if ($pickCount === 0) return;

                $picked = collect($emotionIds)->shuffle()->take($pickCount);
                $attach = [];
                foreach ($picked as $eid) {
                    $attach[$eid] = ['intensity' => rand(1, 10)];
                }
                $entry->emotions()->attach($attach);
            });
        }

        // (F) Seed Tags พื้นฐาน
        $this->call(TagSeeder::class);

        // (G) แนบ Tags ให้แต่ละ DiaryEntry แบบสุ่ม (0–3 อัน) (idempotent)
        $tagIds = Tag::pluck('id')->all();
        if (!empty($tagIds)) {
            DiaryEntry::query()->each(function (DiaryEntry $entry) use ($tagIds) {
                if ($entry->tags()->count() > 0) {
                    return; // มีแท็กอยู่แล้ว ข้ามเพื่อให้รันซ้ำได้ปลอดภัย
                }
                $pickCount = rand(0, 3);
                if ($pickCount === 0) return;

                $picked = collect($tagIds)->shuffle()->take($pickCount)->values()->all();
                $entry->tags()->attach($picked);
            });
        }

        // (H) สร้าง Reminders ตัวอย่างให้แต่ละผู้ใช้ (1–3 รายการต่อคน)
        foreach ($users as $user) {
            foreach (range(1, rand(1, 3)) as $i) {
                // ใช้ title+remind_at กันซ้ำแบบคร่าวๆต่อ user
                $title = fake()->sentence(3);
                $remindAt = fake()->optional()->dateTimeBetween('now', '+21 days');

                \App\Models\Reminder::updateOrCreate(
                    ['user_id' => $user->id, 'title' => $title, 'remind_at' => $remindAt],
                    ['notes' => fake()->optional()->paragraph(), 'status' => 'new']
                );
            }
        }

        // (I) แนบ Tags ให้ Reminders แบบสุ่ม (0–3 อัน) (idempotent)
        if (!empty($tagIds)) {
            Reminder::query()->each(function (Reminder $reminder) use ($tagIds) {
                if ($reminder->tags()->count() > 0) {
                    return; // มีแท็กอยู่แล้ว ข้าม
                }
                $pickCount = rand(0, 3);
                if ($pickCount === 0) return;

                $picked = collect($tagIds)->shuffle()->take($pickCount)->values()->all();
                $reminder->tags()->attach($picked);
            });
        }

        // เพิ่ม seeder อื่น ๆ ได้ที่นี่…
    }
}

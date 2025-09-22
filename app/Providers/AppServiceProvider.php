<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\DiaryEntry;
use App\Models\Reminder;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ใช้ alias สั้นๆ สำหรับ polymorphic types
        Relation::enforceMorphMap([
            'diary'    => DiaryEntry::class,
            'reminder' => Reminder::class,
        ]);
    }
}

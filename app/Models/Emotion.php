<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Emotion extends Model
{
    protected $table = 'emotions';
    protected $fillable = ['name', 'description'];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function diaryEntries(): BelongsToMany
    {
        // ถ้าจะใช้ชื่อตาราง pivot เป็น diary_entry_emotions ให้ระบุชื่อไว้
        return $this->belongsToMany(DiaryEntry::class, 'diary_entry_emotions')
                    ->withPivot('intensity')
                    ->withTimestamps();
    }
}

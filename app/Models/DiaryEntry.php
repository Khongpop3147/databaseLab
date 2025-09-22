<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaryEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'content',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // ความสัมพันธ์กับ user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ความสัมพันธ์ many-to-many กับ Emotion (ถ้ามี)
    public function emotions()
    {
        return $this->belongsToMany(Emotion::class, 'diary_entry_emotions')
                    ->withPivot('intensity')
                    ->withTimestamps();
    }
}

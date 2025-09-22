<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class DiaryEntry extends Model
{
    use HasFactory;

    protected $table = 'diary_entries';

    protected $fillable = ['user_id', 'date', 'content'];

    protected $casts = [
        'date'       => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ผู้เขียนบันทึก (One-to-Many: User -> DiaryEntry)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // อารมณ์ (Many-to-Many ผ่าน pivot diary_entry_emotions)
    public function emotions(): BelongsToMany
    {
        return $this->belongsToMany(Emotion::class, 'diary_entry_emotions', 'diary_entry_id', 'emotion_id')
                    ->withPivot('intensity')
                    ->withTimestamps();
    }

    // แท็ก (Polymorphic Many-to-Many ผ่านตาราง taggables)
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable')
                    ->withTimestamps();
    }
}

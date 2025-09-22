<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    protected $fillable = ['name'];

    // ถ้าจะรองรับหลายโมเดล ให้ morphedByMany เพิ่มได้
    public function diaryEntries(): MorphToMany
    {
        return $this->morphedByMany(DiaryEntry::class, 'taggable');
    }
}

<?php

namespace App\Models;

use App\Models\Tag; // ⬅️ เพิ่ม import
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;   // ⬅️ ใช้ factory ได้
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Reminder extends Model
{
    use HasFactory; // ⬅️ ถ้ามี factory

    protected $fillable = ['user_id', 'title', 'notes', 'remind_at', 'status'];

    protected $attributes = [
        'status' => 'new', // ⬅️ ค่าเริ่มต้น (ปรับตามที่ใช้จริง: new|done ฯลฯ)
    ];

    protected $casts = [
        'remind_at'  => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** ใช้แท็กร่วมกับ Diary ผ่าน polymorphic */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }

    /* ---------- Scopes/Helpers (ตัวเลือก) ---------- */

    /** คิวรีเฉพาะของผู้ใช้คนหนึ่ง */
    public function scopeOwnedBy($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /** เตือนที่ยังไม่ถึงกำหนด/อนาคต */
    public function scopeUpcoming($query)
    {
        return $query->where('remind_at', '>=', now());
    }

    /** ทำเครื่องหมายว่าเสร็จแล้ว (ตัวอย่าง helper) */
    public function markDone(): void
    {
        $this->update(['status' => 'done']);
    }
}

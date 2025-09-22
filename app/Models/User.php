<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Reminder; // ⬅️ เพิ่มบรรทัดนี้

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = ['name','email','password','profile_photo','birthdate'];

    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'birthdate'         => 'date',
    ];

    public function socialMediaLinks(): HasMany
    {
        return $this->hasMany(SocialMediaLink::class);
    }

    public function diaryEntries(): HasMany
    {
        return $this->hasMany(DiaryEntry::class);
    }

    public function bio(): HasOne
    {
        return $this->hasOne(UserBio::class, 'user_id');
    }

    /** One user has many reminders */
    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }
}

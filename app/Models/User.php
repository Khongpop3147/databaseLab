<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo',
        'birthdate',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthdate' => 'date',
    ];

    /**
     * Social Media Links relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function socialMediaLinks(): HasMany
    {
        return $this->hasMany(SocialMediaLink::class);
    }

    /**
     * Diary entries relationship (one user has many diary entries)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function diaryEntries(): HasMany
    {
        return $this->hasMany(DiaryEntry::class);
    }

    /**
     * User bio relationship (one-to-one)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function bio(): HasOne
    {
        return $this->hasOne(UserBio::class, 'user_id');
    }
}

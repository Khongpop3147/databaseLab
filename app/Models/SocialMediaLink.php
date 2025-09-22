<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMediaLink extends Model
{
    use HasFactory;

    protected $fillable = ['platform', 'url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

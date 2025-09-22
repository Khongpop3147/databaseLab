<?php

namespace App\Policies;

use App\Models\DiaryEntry;
use App\Models\User;

class DiaryEntryPolicy
{
    public function view(User $user, DiaryEntry $entry): bool
    {
        return $entry->user_id === $user->id;
    }

    public function update(User $user, DiaryEntry $entry): bool
    {
        return $entry->user_id === $user->id;
    }

    public function delete(User $user, DiaryEntry $entry): bool
    {
        return $entry->user_id === $user->id;
    }
}

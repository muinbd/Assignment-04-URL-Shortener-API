<?php

namespace App\Policies;

use App\Models\ShortUrl;
use App\Models\User;

class ShortUrlPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ShortUrl $shortUrl): bool
    {
        return $user->id === $shortUrl->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ShortUrl $shortUrl): bool
    {
        return $user->id === $shortUrl->user_id;
    }

    public function delete(User $user, ShortUrl $shortUrl): bool
    {
        return $user->id === $shortUrl->user_id;
    }
}

<?php

namespace App\Policies;

use App\Models\FeedItem;
use App\Models\User;

class FeedItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FeedItem $feedItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FeedItem $feedItem): bool
    {
        return $user->id === $feedItem->feed->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FeedItem $feedItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FeedItem $feedItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FeedItem $feedItem): bool
    {
        return false;
    }
}

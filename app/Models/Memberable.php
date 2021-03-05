<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait Memberable {

    /**
     * Relationship returns Memberalble members.
     *
     * @return BelongsToMany
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }


    /**
     * Adds new user to Memberalble memebers.
     *
     * @param User $user
     */
    public function joinMember(User $user): void
    {
        $this->members()->attach($user);
    }


    /**
     * Removes user from Memberalble memebers.
     *
     * @param User $user
     */
    public function leaveMember(User $user): void
    {
        $this->members()->detach($user);
    }


    /**
     * Check if user is memeber of Memberalble.
     *
     * @param User|null $user
     * @return bool
     */
    public function isMember(?User $user): bool
    {
        if($user === null)
            return false;

        return !is_null($this->members()
            ->where('user_id', $user->id)
            ->first());
    }


    /**
     * Attribute is member of Memberalble.
     *
     * @return bool
     */
    public function getIsMemberAttribute(): bool
    {
        return $this->isMember(auth()->user());
    }


    /**
     * Attribute is count of members of Memberalble.
     *
     * @return int
     */
    public function getMembersCountAttribute(): int
    {
        return $this->members()->count();
    }
}

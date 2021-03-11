<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Voteable
{
    /**
     * Relationship to votes.
     *
     * @return MorphMany
     */
    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'voteable');
    }


    public function getScoreAttribute()
    {
        return $this->score();
    }

    public function getVotedByUserAttribute()
    {
        if(auth()->guest())
            return null;

        return $this->votes()->select('upvote')->where('user_id', auth()->id())->first();
    }


    public function score()
    {
        $upvotes = $this->votes()->where('upvote', true)->count();
        $downvotes = $this->votes()->where('upvote', false)->count();
        return $upvotes - $downvotes;
    }

    public function upvote(User $user)
    {
        $this->vote($user, true);
    }

    public function downvote(User $user)
    {
        $this->vote($user, false);
    }

    private function vote(User $user, bool $upvote)
    {
        $existingVote = $this->votes()->where(["user_id" => $user->id])->first();

        if($existingVote !== null && $existingVote->upvote == $upvote) {
            $existingVote->delete();
        } else if($existingVote !== null && $existingVote->upvote != $upvote) {
            $existingVote->update([
                "upvote" => $upvote
            ]);
        } else {
            $this->votes()->create([
                "user_id" => $user->id,
                "upvote" => $upvote
            ]);
        }
    }
}

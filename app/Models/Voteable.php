<?php

namespace App\Models;

use App\Events\Voted;
use App\Notifications\NewCommentVote;
use App\Notifications\NewPostVote;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use ReflectionClass;

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

        $notifications = [
            Post::class => NewPostVote::class,
            Comment::class => NewCommentVote::class
        ];

        $model = $notifications[static::class];
        $model_id_title= strtolower((new ReflectionClass($this))->getShortName()) . "_id";

        if($existingVote !== null && $existingVote->upvote == $upvote) {
            // Delete notification
            $this->author->notifications()
                ->where('type', $model)
                ->where("data->$model_id_title", $this->id)
                ->where('data->user_id', auth()->id())
                ->delete();

            $existingVote->delete();
        } else if($existingVote !== null && $existingVote->upvote != $upvote) {
            // Update notification
            $this->author->notifications()
                ->where('type', $model)
                ->where("data->$model_id_title", $this->id)
                ->where('data->user_id', auth()->id())
                ->update(["data->upvote" => $upvote, "read_at" => null]);

            $existingVote->update([
                "upvote" => $upvote
            ]);
        } else {
            // Create notification
            Voted::dispatch($this, auth()->user(), $upvote);

            $this->votes()->create([
                "user_id" => $user->id,
                "upvote" => $upvote
            ]);
        }
    }
}

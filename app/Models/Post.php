<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['post_author', 'sub_page_name', 'score', 'voted_by_user'];

    /**
     * Relationship to author.
     *
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function getPostAuthorAttribute()
    {
        return $this->author()->select(['id', 'name'])->first();
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->diffForHumans();
    }

    public function getSubPageNameAttribute(): string
    {
        return $this->subPage()->select('name')->first()->name;
    }

    public function subPage(): BelongsTo
    {
        return $this->belongsTo(SubPage::class);
    }


    /**
     * Relationship to votes.
     *
     * @return HasMany
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
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

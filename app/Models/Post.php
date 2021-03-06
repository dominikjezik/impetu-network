<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

class Post extends Model
{
    use HasFactory, Voteable;

    protected $guarded = [];
    protected $appends = ['post_author', 'sub_page_name', 'score', 'voted_by_user', 'comment_count', 'comments_list', 'can', 'saved_by_user'];

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
     * @return MorphMany
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function getCommentsListAttribute()
    {
        return $this->comments()->orderBy('created_at', 'desc')->get();
    }

    public function getCommentCountAttribute()
    {
        return $this->comments()->count(); // Vráti len "prvé" komentáre nie vnorené
    }

    public function getCanAttribute()
    {
        return [
            'delete_post' => auth()->check() ? auth()->user()->can('delete', $this) : false,
            'update_post' => auth()->check() ? auth()->user()->can('update', $this) : false,
        ];
    }

    public function path(): string
    {
        return "/r/{$this->subPage->name}/{$this->id}";
    }

    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_posts');
    }

    public function saveForUser(User $user)
    {
        $this->savedByUsers()->syncWithoutDetaching([ 'user_id' => $user->id ]);
    }

    public function removeFromSaved(User $user)
    {
        $this->savedByUsers()->detach([ 'user_id' => $user->id ]);
    }

    public function getSavedByUserAttribute(): bool
    {
        if(auth()->guest()) {
            return false;
        }
        return $this->savedByUsers()->where('user_id', auth()->user()->id)->count() > 0;
    }

}

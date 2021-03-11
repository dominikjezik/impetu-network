<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

class Post extends Model
{
    use HasFactory, Voteable;

    protected $guarded = [];
    protected $appends = ['post_author', 'sub_page_name', 'score', 'voted_by_user', 'comments_list'];

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

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Comment extends Model
{
    use HasFactory, Voteable;

    protected $guarded = [];
    protected $appends = ['comments_list', 'comment_author', 'score', 'voted_by_user', 'can'];

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->diffForHumans();
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function author()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function getCommentsListAttribute()
    {
        return $this->comments()->get();
    }

    public function getCommentAuthorAttribute()
    {
        return $this->author()->select(['id', 'name'])->first();
    }

    public function getCanAttribute()
    {
        return [
            'delete_comment' => auth()->user()->can('delete', $this),
            'update_comment' => auth()->user()->can('update', $this),
        ];
    }

    public function subPage(): BelongsTo
    {
        return $this->belongsTo(SubPage::class);
    }

}

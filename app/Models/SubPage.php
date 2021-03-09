<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use phpDocumentor\Reflection\Types\Boolean;

class SubPage extends Model
{
    use HasFactory, Memberable;

    protected $guarded = [];

    /**
     * Appends attributes to the Sub page.
     *
     * @var string[]
     */
    protected $appends = ['is_member', 'members_count', 'latest_posts'];


    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): String
    {
        return "name";
    }

    /**
     * Relationship to posts.
     *
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Posts ordered by created_at
     *
     * @return HasMany
     */
    public function getLatestPostsAttribute()
    {
        return $this->posts()->orderBy("created_at", 'DESC')->get();
    }


    /**
     * Returns path to the Sub page.
     *
     * @return String
     */
    public function path(): String
    {
        return "/r/$this->name";
    }




}

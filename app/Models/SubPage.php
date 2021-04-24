<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubPage extends Model
{
    use HasFactory, Memberable, Roleable;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function(SubPage $subPage) {
             $subPage->setInitialOwner(auth()->user());
        });
    }

    /**
     * Appends attributes to the Sub page.
     *
     * @var string[]
     */
    protected $appends = ['is_member', 'members_count', 'can']; // 'latest_posts'


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
     * @return Collection
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

    public function getCanAttribute()
    {
        return [
            'delete_sub_page' => auth()->user()->can('delete', $this),
            'change_owner_of_sub_page' => auth()->user()->can('changeOwner', $this),
            'manage_sub_page' => auth()->user()->can('manage', $this),
            'update_basic_information' => auth()->user()->can('update', $this)
        ];
    }




}

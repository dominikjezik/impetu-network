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
            if(!auth()->check()) {
                throw new \Exception("No owner assigned for new community.");
            }
            $subPage->setInitialOwner(auth()->user());
        });
    }

    /**
     * Appends attributes to the Sub page.
     *
     * @var string[]
     */
    protected $appends = ['is_member', 'members_count', 'can', 'photo_url']; // 'latest_posts'


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
            'delete_sub_page' => auth()->check() ? auth()->user()->can('delete', $this) : false,
            'change_owner_of_sub_page' => auth()->check() ? auth()->user()->can('changeOwner', $this) : false,
            'manage_sub_page' => auth()->check() ? auth()->user()->can('manage', $this) : false,
            'update_basic_information' => auth()->check() ? auth()->user()->can('update', $this) : false,
            'update_appearance' => auth()->check() ? auth()->user()->can('update', $this) : false,
        ];
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function attachNewPhoto(string $path)
    {
         $this->media()->create([
            'path' => $path,
            'type' => 'photo'
        ]);
    }

    public function attachNewBanner(string $path)
    {
        $this->media()->create([
            'path' => $path,
            'type' => 'photo_banner'
        ]);
    }

    public function photo()
    {
        return $this->media()->where('type', 'photo');
    }

    public function banner()
    {
        return $this->media()->where('type', 'photo_banner');
    }

    public function getPhotoUrlAttribute(): ?string
    {
        $media = $this->photo->first();
        if(is_null($media)) {
            return null;
        }
        return "/media/{$media->path}";
    }

    public function getBannerUrlAttribute()
    {
        $media = $this->banner->first();
        if(is_null($media)) {
            return null;
        }
        return "/media/{$media->path}";
    }



}

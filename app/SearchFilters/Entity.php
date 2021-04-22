<?php

namespace App\SearchFilters;

use App\Models\Post;
use App\Models\SubPage;
use App\Models\User;
use Closure;

class Entity
{
    private array $entities = [
        "communities" => SubPage::class,
        "users" => User::class,
        "posts" => Post::class
    ];

    public function handle($selectedEntity, Closure $next)
    {
        if(!$selectedEntity) {
            return $next($this->entities['communities']::query());
        }

        if(!array_key_exists($selectedEntity, $this->entities)) {
            throw new \Exception();
        }

        return $next($this->entities[$selectedEntity]::query());
    }
}

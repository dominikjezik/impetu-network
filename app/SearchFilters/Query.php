<?php

namespace App\SearchFilters;

use App\Models\Post;
use App\Models\SubPage;
use App\Models\User;
use Closure;

class Query
{
    private array $searchableFields = [
        SubPage::class => ['name', 'title', 'description'],
        User::class => ['name', 'email'],
        Post::class => ['title', 'body']
    ];

    public function handle($builder, Closure $next)
    {
        if(!request()->has('q')) {
            return $next($builder);
        }

        $builder = $next($builder);

        if(!array_key_exists(get_class($builder->getModel()), $this->searchableFields)) {
            throw new \Exception();
        }

        $conditions = $this->searchableFields[get_class($builder->getModel())];

        return $builder->whereLike($conditions, request()->get('q'));
    }
}

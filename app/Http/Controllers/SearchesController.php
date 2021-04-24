<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Post;
use App\Models\SubPage;
use App\Models\User;
use App\SearchFilters\Entity;
use App\SearchFilters\Query;
use App\SearchFilters\UsersFromCommunity;
use Illuminate\Pipeline\Pipeline;

class SearchesController extends Controller
{
    private array $pipes = [
        Query::class,
        Entity::class,
    ];

    public function index(Pipeline $pipeline)
    {
        $builder = $pipeline->send(request()->get('entity')) //query()
            ->through($this->pipes)
            ->thenReturn();

        return $builder->get()->each(fn($post) => $post->setAppends([]));

//        $builder = $pipeline->send(Post::select(['id', 'title', 'body'])) //query()
//            ->through($this->pipes)
//            ->thenReturn();
//
//        $result = $builder->get()->each(fn($post) => $post->setAppends([]));
//
//        return $result;
    }

    public function indexApi(SearchRequest $request, Pipeline $pipeline)
    {
        $builder = $pipeline->send(request()->get('entity')) //query()
            ->through($this->pipes)
            ->thenReturn();

        $results = $builder->get();//->each(fn($post) => $post->setAppends([]));

        if(request()->has('usersFrom')) {
            $results = $results->filter(function($u) {
                return $u->joinedCommunities()->where('name', request()->get('usersFrom'))->count() === 1;
            });
        }

        return response()->json(['data' => $results]);
    }

}

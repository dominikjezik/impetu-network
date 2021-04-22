<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Post;
use App\Models\SubPage;
use App\Models\User;
use App\SearchFilters\Entity;
use App\SearchFilters\Query;
use Illuminate\Pipeline\Pipeline;

class SearchesController extends Controller
{
    private array $pipes = [
        Query::class,
        Entity::class
    ];

    public function index(Pipeline $pipeline)
    {
        $builder = $pipeline->send(request()->get('entity')) //query()
            ->through($this->pipes)
            ->thenReturn();

        $results = $builder->get()->each(fn($post) => $post->setAppends([]));

        return $results;

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
        //$model= $this->entities["users"];

        //return $model::query()->get();

//        if($request->validated()['entity']) {
//
//        }

        $builder = $pipeline->send(request()->get('entity')) //query()
            ->through($this->pipes)
            ->thenReturn();

        $results = $builder->get();//->each(fn($post) => $post->setAppends([]));

        return response()->json(['data' => $results]);



        $results = SubPage::whereLike(['name', 'title', 'description'], $request->validated()['q'])->get();
        $results->each(fn($post) => $post->setAppends(['members_count']));
        return response()->json(['data' => $results]);
    }

}

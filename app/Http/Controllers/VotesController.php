<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\SubPage;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VotesController extends Controller
{

    /**
     * @param SubPage $subPage
     * @param Post $post
     * @return Application|ResponseFactory|Response
     */
    public function storeUpvote(SubPage $subPage, Post $post)
    {
        $post->upvote(auth()->user());
        return response("Upvoted!", 201);
    }


    /**
     * @param SubPage $subPage
     * @param Post $post
     */
    public function storeDownvote(SubPage $subPage, Post $post)
    {
        $post->downvote(auth()->user());
    }

}

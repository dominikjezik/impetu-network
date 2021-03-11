<?php

namespace App\Http\Controllers;

use App\Models\Comment;
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
    public function storePostUpvote(SubPage $subPage, Post $post)
    {
        $post->upvote(auth()->user());
        return response("Upvoted!", 201);
    }


    /**
     * @param SubPage $subPage
     * @param Post $post
     * @return Application|ResponseFactory|Response
     */
    public function storePostDownvote(SubPage $subPage, Post $post)
    {
        $post->downvote(auth()->user());
        return response("Downvoted!", 201);
    }


    /**
     * @param SubPage $subPage
     * @param Post $post
     * @param Comment $comment
     * @return Application|ResponseFactory|Response
     */
    public function storeCommentUpvote(SubPage $subPage, Post $post, Comment $comment)
    {
        $comment->upvote(auth()->user());
        return response("Upvoted!", 201);
    }


    /**
     * @param SubPage $subPage
     * @param Post $post
     * @param Comment $comment
     * @return Application|ResponseFactory|Response
     */
    public function storeCommentDownvote(SubPage $subPage, Post $post, Comment $comment)
    {
        $comment->downvote(auth()->user());
        return response("Downvoted!", 201);
    }



}

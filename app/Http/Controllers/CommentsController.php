<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Models\SubPage;
use Illuminate\Database\Eloquent\Model;

class CommentsController extends Controller
{
    /**
     * Store a newly created comment in storage.
     *
     * @param StoreCommentRequest $request
     * @param SubPage $subPage
     * @param Post $post
     * @param Comment $comment
     * @return Model
     */
    public function store(StoreCommentRequest $request, SubPage $subPage, Post $post, Comment $comment)
    {
        $newCommentBody = [
            'user_id' => auth()->id(),
            'body' => clean($request->get('body'))
        ];

        if(empty($comment->id)) {
            $newComment = $post->comments()->create($newCommentBody);
        } else {
            $newComment = $comment->comments()->create($newCommentBody);
        }

        return $newComment;
    }
}

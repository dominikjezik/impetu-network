<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Models\SubPage;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

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
            'body' => clean($request->get('body')),
            'sub_page_id' => $subPage->id
        ];

        if(empty($comment->id)) {
            $newComment = $post->comments()->create($newCommentBody);
        } else {
            $newComment = $comment->comments()->create($newCommentBody);
        }

        return $newComment;
    }

    /**
     * Update the specified comment in storage.
     *
     * @param UpdateCommentRequest $request
     * @param SubPage $subPage
     * @param Post $post
     * @param Comment $comment
     * @return string[]
     */
    public function update(UpdateCommentRequest $request, SubPage $subPage, Post $post, Comment $comment)
    {
        $comment->update($request->validated());
        return ["message" => "updated"];
    }

    /**
     * Remove the specified comment from storage.
     *
     * @param SubPage $subPage
     * @param Post $post
     * @param Comment $comment
     * @return string[]
     * @throws Exception
     */
    public function destroy(SubPage $subPage, Post $post, Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return ["message" => "deleted"];
    }

}

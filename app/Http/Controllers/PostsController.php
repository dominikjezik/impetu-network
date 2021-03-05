<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Models\SubPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new post.
     *
     * @return Response
     */
    public function create(?SubPage $subPage)
    {
        $parms = [
            'list_of_communities' => auth()->user()->getTitlesOfJoinedCommunities
        ];
        if(!empty($subPage->id)) {
            $parms["subpage"] = $subPage;
        }
        return Inertia::render('Posts/Create', $parms);
    }

    /**
     * Store a newly created post in database.
     *
     * @param StorePostRequest $request
     * @param SubPage $subPage
     * @return RedirectResponse
     */
    public function store(StorePostRequest $request, SubPage $subPage)
    {
        $subPage->posts()->create(array_merge($request->validated(), [
            'user_id' => auth()->id()
        ]));

        return redirect($subPage->path());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}

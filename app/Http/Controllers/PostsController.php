<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Models\SubPage;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Inertia\Inertia;
use Inertia\Response;

class PostsController extends Controller
{
    /**
     * Show the form for creating a new post.
     *
     * @param SubPage $subPage
     * @return Response
     */
    public function create(SubPage $subPage)
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
        $validated = $request->validated();
        $validated["body"] = clean($validated["body"]);
        $validated['user_id'] = auth()->id();

        $subPage->posts()->create($validated);

        return redirect($subPage->path());
    }

    /**
     * Display the specified post.
     *
     * @param SubPage $subPage
     * @param Post $post
     * @return Response
     */
    public function show(SubPage $subPage, Post $post)
    {
        abort_if($post->subPage->isNot($subPage), 404);

        return Inertia::render('Posts/Show', [
            'post' => $post
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Post $post
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
     * @param Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified post from storage.
     *
     * @param SubPage $subPage
     * @param Post $post
     * @return Application|RedirectResponse|Redirector
     * @throws Exception
     */
    public function destroy(SubPage $subPage,Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return redirect($subPage->path());
    }
}

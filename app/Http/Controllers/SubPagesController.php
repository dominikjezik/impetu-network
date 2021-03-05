<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\SubPage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubPagesController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified SubPage.
     *
     * @param SubPage $subPage
     * @return Response
     */
    public function show(SubPage $subPage)
    {
        $subPage->latestPosts->each(fn($post) => $post->author);
        return Inertia::render('SubPage/Show', ['subpage' => $subPage]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param SubPage $subPage
     * @return \Illuminate\Http\Response
     */
    public function edit(SubPage $subPage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param SubPage $subPage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubPage $subPage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SubPage $subPage
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubPage $subPage)
    {
        //
    }
}

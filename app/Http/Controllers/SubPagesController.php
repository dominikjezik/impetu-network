<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubPageRequest;
use App\Models\Post;
use App\Models\SubPage;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
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
     * Show the form for creating a new Sub page.
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render('SubPage/Create');
    }

    /**
     * Store a newly created Sub page in storage.
     *
     * @param StoreSubPageRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(StoreSubPageRequest $request)
    {
        $subPage = SubPage::create($request->validated());
        return redirect($subPage->path());
    }

    /**
     * Display the specified SubPage.
     *
     * @param SubPage $subPage
     * @return Response
     */
    public function show(SubPage $subPage)
    {
        $subPage->append('latest_posts');
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

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubPageRequest;
use App\Http\Requests\UpdateSubPageRequest;
use App\Models\SubPage;
use Exception;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SubPagesController extends Controller
{
    /**
     * Show the form for creating a new Sub page.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('SubPage/Create');
    }

    /**
     * Store a newly created SubPage in database.
     *
     * @param StoreSubPageRequest $request
     * @return RedirectResponse
     */
    public function store(StoreSubPageRequest $request): RedirectResponse
    {
        $subPage = SubPage::create($request->validated());
        return redirect($subPage->path())->with("message", "Community created successfully!");
    }

    /**
     * Display the specified SubPage.
     *
     * @param SubPage $subPage
     * @return Response
     */
    public function show(SubPage $subPage): Response
    {
        $subPage->append(['latest_posts', 'banner_url']);
        return Inertia::render('SubPage/Show', ['subpage' => $subPage]);
    }

    /**
     * Show the form for editing the specified SubPage.
     *
     * @param SubPage $subPage
     * @return Response
     */
    public function edit(SubPage $subPage): Response
    {
        $subPage->append(['roles', 'can', 'banner_url']);
        return Inertia::render('SubPage/Edit', ['subpage' => $subPage]);
    }

    /**
     * Update the specified SubPage in database.
     *
     * @param UpdateSubPageRequest $request
     * @param SubPage $subPage
     * @return RedirectResponse
     */
    public function update(UpdateSubPageRequest $request, SubPage $subPage): RedirectResponse
    {
        $subPage->update($request->validated());
        return back()->with("message", "Community updated successfully!");
    }

    /**
     * Remove the specified SubPage from datbase.
     *
     * @param SubPage $subPage
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(SubPage $subPage): RedirectResponse
    {
        $this->authorize('delete', $subPage);
        $subPage->delete();
        return redirect()->route('home')->with("message", "Community deleted successfully!");
    }
}

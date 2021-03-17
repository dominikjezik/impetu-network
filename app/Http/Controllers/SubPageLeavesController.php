<?php

namespace App\Http\Controllers;

use App\Models\SubPage;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class SubPageLeavesController extends Controller
{
    /**
     * Removes a user from the Sub page members.
     *
     * @param SubPage $subPage
     * @return Application|RedirectResponse|Redirector
     */
    public function store(SubPage $subPage)
    {
        if(!$subPage->isMember(auth()->user())) {
            return back();
        }

        $subPage->leaveMember(auth()->user());

        return redirect($subPage->path());
    }

}

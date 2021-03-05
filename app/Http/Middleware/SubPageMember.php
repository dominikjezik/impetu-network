<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SubPageMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $subPage = $request->route('subPage');

        if(!$subPage->isMember(auth()->user())) {
            return back()->with("error", "You must be a member to publish posts.");
        }

        return $next($request);
    }
}

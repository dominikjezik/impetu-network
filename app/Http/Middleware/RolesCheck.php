<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RolesCheck
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $subPage = $request->route('subPage');
        abort_if(!$subPage->isAtLeast($role, auth()->user()), 403);

        return $next($request);
    }
}

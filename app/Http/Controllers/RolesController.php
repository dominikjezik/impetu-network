<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Models\SubPage;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RolesController extends Controller
{
    /**
     * Assign a role to a new user if possible.
     *
     * @param StoreRoleRequest $request
     * @param SubPage $subPage
     */
    public function store(StoreRoleRequest $request, SubPage $subPage)
    {
        $role = $request->get('role');
        $user = User::findOrFail($request->get('user'));
        $subPage->tryToSetRole($role, $user);

        return response()->json(['status' => 'created'], 201);
        //return redirect($subPage->path());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the role of the logged in user.
     * The user has given up his role.
     *
     * @param SubPage $subPage
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(SubPage $subPage)
    {
        abort_if($subPage->isOwner(auth()->user()), 403);
        $subPage->removeRole(auth()->user());

        return redirect($subPage->path());
    }

    /**
     * Remove the role of the selected user if possible.
     *
     * @param DestroyRoleRequest $request
     * @param SubPage $subPage
     * @return JsonResponse
     */
    public function destroySomeoneElseRole(DestroyRoleRequest $request, SubPage $subPage)
    {
        $user = User::findOrFail($request->get('user'));
        $subPage->tryToRemoveRole($user);

        return response()->json(['status' => 'deleted']);
    }
}

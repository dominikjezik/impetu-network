<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

trait Roleable
{
    public function rolesRelationship(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function getRolesAttribute(): Collection
    {
        $roles = $this->rolesRelationship()->select(['*'])->get();
        $roles->each(function($role) {
            $role['user'] = User::find($role['user_id']);
        });

        return $roles;
    }

    public function setInitialOwner(User $user): void
    {
        $this->joinMember($user);
        $this->rolesRelationship()->attach([
            'role_id' => Role::where('type', 'owner')->first()->id
        ], [
            'user_id' => $user->id,
        ]);
    }

    public function tryToSetRole(string $role, User $user): void
    {
        // Abort if user already has a role
        abort_if($this->hasAnyRole($user), 403);

        // Only owner of SubPage can set new admin & only one owner can exits
        if($role === "admin") {
            abort_if(!$this->isOwner(auth()->user()), 403);
        } else if($role === "owner") {
            abort(403);
        }

        $this->setRole($role, $user);
    }

    public function setRole(string $roleType, User $user): void
    {
        $this->rolesRelationship()->attach([
            'role_id' => Role::where('type', $roleType)->first()->id
        ], [
            'user_id' => $user->id
        ]);
    }

    public function getRole(User $user): ?Role
    {
        return $this->rolesRelationship()->where(['user_id' => $user->id])->first();
    }

    private function checkRole(string $roleType, User $user): bool
    {
        return $this->rolesRelationship()->where(['user_id' => $user->id, 'type' => $roleType])->count();
    }

    public function hasAnyRole(User $user): bool
    {
        return $this->rolesRelationship()->where(['user_id' => $user->id])->count();
    }

    public function isOwner(User $user): bool
    {
        return $this->checkRole('owner', $user);
    }

    public function isAdmin(User $user): bool
    {
        return $this->checkRole('admin', $user);
    }

    public function isModerator(User $user): bool
    {
        return $this->checkRole('moderator', $user);
    }

    public function isAtLeast(string $role, User $user): bool
    {
        switch($role) {
            case "moderator":
                return $this->rolesRelationship()
                    ->where('user_id', $user->id)
                    ->where(function ($query) {
                        $query->where('type', 'moderator')
                            ->orWhere('type', 'admin')
                            ->orWhere('type', 'owner');
                    })
                    ->count();
            case "admin":
                return $this->rolesRelationship()
                            ->where('user_id', $user->id)
                            ->where(function ($query) {
                                $query->orWhere('type', 'admin')
                                    ->orWhere('type', 'owner');
                            })
                            ->count();
            case "owner":
                return $this->isOwner($user);
            default:
                return false;
        }
    }

    public function tryToRemoveRole(User $user): void
    {
        switch($this->getRole(auth()->user())->type) {
            // Ak je prihlasený User admin, nemôže odstrániť iného admina alebo ownera. (môže iba moderátora)
            case "admin":
                abort_if(
                    $this->getRole($user)->type === "admin" ||
                    $this->getRole($user)->type === "owner",
                    403);
                break;
            // Ak je prihlásený User owner, nemôže odstrániť iného ownera. (môže moderátora a admina)
            case "owner":
                abort_if($this->getRole($user)->type === "owner", 403);
        }

        $this->removeRole($user);
    }

    public function removeRole(User $user): void
    {
        DB::table('role_user')
            ->where('sub_page_id', $this->id)
            ->where('user_id', $user->id)
            ->delete();
    }



}

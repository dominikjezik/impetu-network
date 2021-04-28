<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\SubPage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostSavesController extends Controller
{
    public function index()
    {
        return Inertia::render("SavedPosts", [
            "posts" => auth()->user()->savedPosts
        ]);
    }

    public function store(Request $request, SubPage $subPage, Post $post)
    {
        $post->saveForUser(auth()->user());
        return response("Saved");
    }

    public function destroy(SubPage $subPage, Post $post)
    {
        $post->removeFromSaved(auth()->user());
        return response("Removed");
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Display user timeline.
     *
     * @return mixed
     */
    public function index()
    {
        if(auth()->guest()) {
            return Inertia::render('GuestHome');
        }
        return Inertia::render('Home', [
            'posts' => Post::all()->each(fn($post) => $post->author)
        ]);
    }
}

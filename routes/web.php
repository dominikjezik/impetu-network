<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\SearchesController;
use App\Http\Controllers\SubPageJoinsController;
use App\Http\Controllers\SubPageLeavesController;
use App\Http\Controllers\SubPagesController;
use App\Http\Controllers\VotesController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/', [HomeController::class, 'index'])->name('home');

// Search page
Route::get('/search', [SearchesController::class, 'index']);

// Serach api
Route::get('/api/search', [SearchesController::class, 'indexApi']);


Route::middleware('auth')->group(function() {
    // Publish new post form
    Route::get('/publish', [PostsController::class, 'create'])->name('posts.create');

    // Create new Sub page
    Route::get('/create-community', [SubPagesController::class, 'create'])->name('subpages.create');

    // Store new Sub page
    Route::post('/create-community', [SubPagesController::class, 'store'])->name('subpages.store');

});

Route::prefix('/r/{subPage}')->group( function() {
    // Index Sub page
    Route::get('/', [SubPagesController::class, 'show']);

    // Edit Sub page form
    Route::get('/manage', [SubPagesController::class, 'edit']);

    Route::middleware('auth')->group(function() {

        // Publish new post form
        Route::get('/publish', [PostsController::class, 'create'])->name('posts.create-specific');

        // Publish new post
        Route::post('/posts', [PostsController::class, 'store'])->middleware('member')->name('posts.store');

        // Edit post form
        Route::get('/{post}/edit', [PostsController::class, 'edit'])->name('posts.edit');

        // Edit post
        Route::patch('/{post}', [PostsController::class, 'update'])->name('posts.update');

        // Delete post
        Route::delete('/{post}', [PostsController::class, 'destroy'])->name('posts.destroy');

        // Join/Leave sub page.
        Route::post('/join', [SubPageJoinsController::class, 'store'])->name('subpages.join');
        Route::post('/leave', [SubPageLeavesController::class, 'store'])->name('subpages.leave');

        // Publish comment to post
        Route::post('/{post}/comments', [CommentsController::class, 'store'])->middleware('member');

        // Publish comment to comment
        Route::post('/{post}/comments/{comment}', [CommentsController::class, 'store'])->middleware('member');

        // Delete comment
        Route::delete('/{post}/comments/{comment}', [CommentsController::class, 'destroy']);

        // Edit comment
        Route::patch('/{post}/comments/{comment}', [CommentsController::class, 'update']);

        // Up and Down vote post
        Route::post('/{post}/upvote', [VotesController::class, 'storePostUpvote']);
        Route::post('/{post}/downvote', [VotesController::class, 'storePostDownvote']);

        // Up and Down vote comment
        Route::post('/{post}/comments/{comment}/upvote', [VotesController::class, 'storeCommentUpvote']);
        Route::post('/{post}/comments/{comment}/downvote', [VotesController::class, 'storeCommentDownvote']);

    });

    // Show post
    Route::get('/{post}', [PostsController::class, 'show'])->name('posts.show');

});






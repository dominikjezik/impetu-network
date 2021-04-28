<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostSavesController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\RolesController;
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

// All saved users
Route::get('/saved', [PostSavesController::class, 'index'])->middleware('auth')->name('posts.saved.index');

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
    Route::get('/', [SubPagesController::class, 'show'])->name('subpages.show');

    Route::middleware('auth')->group(function() {
        // Delete Sub page
        Route::delete('/', [SubPagesController::class, 'destroy'])->name('subpages.destroy');

        Route::prefix('/manage')->group(function () {
            // Edit SubPage form
            Route::get('/', [SubPagesController::class, 'edit'])->middleware('role:moderator')->name('subpages.edit');

            // Update SubPage
            Route::patch('/', [SubPagesController::class, 'update'])->middleware('role:admin')->name('subpages.udpate');

            // Store role for new user
            Route::post('/manage/role', [RolesController::class, 'store'])->middleware('role:admin')->name('roles.store');

            // Destroy user role
            Route::delete('/api/manage/role', [RolesController::class, 'destroySomeoneElseRole'])->middleware('role:admin')->name('roles.destroySomeoneElseRole');

            // Destroy user role - giveup
            Route::delete('/manage/role-giveup', [RolesController::class, 'destroy'])->middleware('role:moderator')->name('roles.destroy');
        });


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
        Route::post('/{post}/comments', [CommentsController::class, 'store'])->middleware('member')->name('comments.post.store');

        // Publish comment to comment
        Route::post('/{post}/comments/{comment}', [CommentsController::class, 'store'])->middleware('member')->name('comments.comment.store');

        // Delete comment
        Route::delete('/{post}/comments/{comment}', [CommentsController::class, 'destroy'])->name('comments.destroy');

        // Edit comment
        Route::patch('/{post}/comments/{comment}', [CommentsController::class, 'update'])->name('comments.update');

        // Up and Down vote post
        Route::post('/{post}/upvote', [VotesController::class, 'storePostUpvote'])->name('posts.upvote');
        Route::post('/{post}/downvote', [VotesController::class, 'storePostDownvote'])->name('posts.downvote');

        // Up and Down vote comment
        Route::post('/{post}/comments/{comment}/upvote', [VotesController::class, 'storeCommentUpvote'])->name('comments.upvote');
        Route::post('/{post}/comments/{comment}/downvote', [VotesController::class, 'storeCommentDownvote'])->name('comments.downvote');

        // Save post
        Route::post('/{post}/save', [PostSavesController::class, 'store'])->name('posts.save.store');

        // Remove saved post
        Route::delete('/{post}/save', [PostSavesController::class, 'destroy'])->name('posts.save.destroy');

    });

    // Show post
    Route::get('/{post}', [PostsController::class, 'show'])->name('posts.show');

});






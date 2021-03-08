<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\SubPageJoinsController;
use App\Http\Controllers\SubPageLeavesController;
use App\Http\Controllers\SubPagesController;
use App\Http\Controllers\VotesController;
use Illuminate\Foundation\Application;
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

//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

/*
 * Routes
 *
 * /r/{subPage}/{id}                zobrazenie konkrétneho príspevku v Sub page
 * /r/{subPage}/posts/create        formulár vytvorenie príspevku
 *
 */

Route::get('/', [HomeController::class, 'index']);

// Publish new post form
Route::get('/publish', [PostsController::class, 'create']);

Route::prefix('/r/{subPage}')->group( function() {

    // Index Sub page
    Route::get('/', [SubPagesController::class, 'show']);

    // Join/Leave sub page.
    Route::post('/join', [SubPageJoinsController::class, 'store']);
    Route::post('/leave', [SubPageLeavesController::class, 'store']);

    // Publish new post form
    Route::get('/publish', [PostsController::class, 'create']);

    // Publish new post
    Route::post('/posts', [PostsController::class, 'store'])->middleware('member');

    // Up and Down vote post
    Route::post('/{post}/upvote', [VotesController::class, 'storeUpvote'])->middleware('auth');
    Route::post('/{post}/downvote', [VotesController::class, 'storeDownvote'])->middleware('auth');

});





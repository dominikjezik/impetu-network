<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\SubPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_post_can_have_comments()
    {
//        $user = User::factory()->create();
//        SubPage::factory()->create();
//
//        $post = Post::factory()->create();
//
//        $post->comments;
//
//        Comment::create([
//            'user_id' => $user->id,
//            'body' => 'something',
//            'commentable_type' => Post::class
//        ]);
    }

}

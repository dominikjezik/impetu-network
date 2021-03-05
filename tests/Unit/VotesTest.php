<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\SubPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_user_can_up_vote_a_post()
    {
        $user = User::factory()->create();
        SubPage::factory()->create();

        $post = Post::factory()->create();

        $post->upvote($user);

        $this->assertEquals(1, $post->score());
    }

    /** @test */
    public function an_user_can_reset_up_vote_a_post()
    {
        $user = User::factory()->create();
        SubPage::factory()->create();

        $post = Post::factory()->create();

        $post->upvote($user);
        $post->upvote($user);

        $this->assertEquals(0, $post->score());
    }

    /** @test */
    public function an_user_can_up_vote_after_reset()
    {
        $user = User::factory()->create();
        SubPage::factory()->create();

        $post = Post::factory()->create();

        $post->upvote($user);
        $post->upvote($user);
        $post->upvote($user);

        $this->assertEquals(1, $post->score());
    }

    /** @test */
    public function an_user_can_down_vote_a_post()
    {
        $user = User::factory()->create();
        SubPage::factory()->create();

        $post = Post::factory()->create();

        $post->downvote($user);

        $this->assertEquals(-1, $post->score());
    }

    /** @test */
    public function an_user_can_reset_down_vote_a_post()
    {
        $user = User::factory()->create();
        SubPage::factory()->create();

        $post = Post::factory()->create();

        $post->downvote($user);
        $post->downvote($user);

        $this->assertEquals(0, $post->score());
    }

    /** @test */
    public function an_user_can_down_vote_after_reset()
    {
        $user = User::factory()->create();
        SubPage::factory()->create();

        $post = Post::factory()->create();

        $post->downvote($user);
        $post->downvote($user);
        $post->downvote($user);

        $this->assertEquals(-1, $post->score());
    }

    /** @test */
    public function an_user_can_change_upvote_to_downvote()
    {
        $user = User::factory()->create();
        SubPage::factory()->create();

        $post = Post::factory()->create();

        $post->upvote($user);
        $this->assertEquals(1, $post->score());
        $post->downvote($user);
        $this->assertEquals(-1, $post->score());
    }

    /** @test */
    public function an_user_can_change_downvote_to_upvote()
    {
        $user = User::factory()->create();
        SubPage::factory()->create();

        $post = Post::factory()->create();

        $post->downvote($user);
        $this->assertEquals(-1, $post->score());
        $post->upvote($user);
        $this->assertEquals(1, $post->score());
    }

}

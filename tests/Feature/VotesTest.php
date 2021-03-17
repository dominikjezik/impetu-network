<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\SubPage;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

class VotesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_user_can_up_vote_a_post()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $this->post("/r/{$post->subPage->name}/$post->id/upvote");

        $this->assertEquals(1, $post->score());
    }

    /** @test */
    public function an_user_can_reset_up_vote_a_post()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $this->post("/r/{$post->subPage->name}/$post->id/upvote");

        $this->assertEquals(1, $post->score());

        $this->post("/r/{$post->subPage->name}/$post->id/upvote");
        $this->assertEquals(0, $post->score());
    }

    /** @test */
    public function an_user_can_down_vote_a_post()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $this->post("/r/{$post->subPage->name}/$post->id/downvote");

        $this->assertEquals(-1, $post->score());
    }

    /** @test */
    public function an_user_can_reset_down_vote_a_post()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $subPage = SubPage::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $this->post("/r/$subPage->name/$post->id/downvote");

        $this->assertEquals(-1, $post->score());

        $this->post("/r/$subPage->name/$post->id/downvote");
        $this->assertEquals(0, $post->score());
    }

    /** @test */
    public function an_user_can_change_upvote_to_downvote_a_post()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $this->post("/r/{$post->subPage->name}/$post->id/upvote");

        $this->assertEquals(1, $post->score());

        $this->post("/r/{$post->subPage->name}/$post->id/downvote");
        $this->assertEquals(-1, $post->score());
    }

    /** @test */
    public function an_user_can_change_downvote_to_upvote_a_post()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $subPage = SubPage::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $this->post("/r/$subPage->name/$post->id/downvote");

        $this->assertEquals(-1, $post->score());

        $this->post("/r/$subPage->name/$post->id/upvote");
        $this->assertEquals(1, $post->score());
    }

    /** @test */
    public function an_user_can_up_vote_a_comment()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->actingAs($user);

        $this->post("/r/{$comment->commentable->subPage->name}/{$comment->commentable->id}/comments/$comment->id/upvote");

        $this->assertEquals(1, $comment->score());
    }

    /** @test */
    public function an_user_can_reset_up_vote_a_comment()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->actingAs($user);

        $this->post("/r/{$comment->commentable->subPage->name}/{$comment->commentable->id}/comments/$comment->id/upvote");

        $this->assertEquals(1, $comment->score());

        $this->post("/r/{$comment->commentable->subPage->name}/{$comment->commentable->id}/comments/$comment->id/upvote");
        $this->assertEquals(0, $comment->score());
    }

    /** @test */
    public function an_user_can_down_vote_a_comment()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->actingAs($user);

        $this->post("/r/{$comment->commentable->subPage->name}/{$comment->commentable->id}/comments/$comment->id/downvote");

        $this->assertEquals(-1, $comment->score());
    }

    /** @test */
    public function an_user_can_reset_down_vote_a_comment()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->actingAs($user);

        $this->post("/r/{$comment->commentable->subPage->name}/{$comment->commentable->id}/comments/$comment->id/downvote");

        $this->assertEquals(-1, $comment->score());

        $this->post("/r/{$comment->commentable->subPage->name}/{$comment->commentable->id}/comments/$comment->id/downvote");
        $this->assertEquals(0, $comment->score());
    }

    /** @test */
    public function an_user_can_change_upvote_to_downvote_a_comment()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->actingAs($user);

        $this->post("/r/{$comment->commentable->subPage->name}/{$comment->commentable->id}/comments/$comment->id/upvote");

        $this->assertEquals(1, $comment->score());

        $this->post("/r/{$comment->commentable->subPage->name}/{$comment->commentable->id}/comments/$comment->id/downvote");
        $this->assertEquals(-1, $comment->score());
    }

    /** @test */
    public function an_user_can_change_downvote_to_upvote_a_comment()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->actingAs($user);

        $this->post("/r/{$comment->commentable->subPage->name}/{$comment->commentable->id}/comments/$comment->id/downvote");

        $this->assertEquals(-1, $comment->score());

        $this->post("/r/{$comment->commentable->subPage->name}/{$comment->commentable->id}/comments/$comment->id/upvote");
        $this->assertEquals(1, $comment->score());
    }

}

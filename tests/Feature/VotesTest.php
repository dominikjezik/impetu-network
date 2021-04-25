<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Role;
use App\Models\SubPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VotesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
        $this->setInitialRoles();
        SubPage::factory()->create();
        auth()->logout();
    }

    private function setInitialRoles()
    {
        Role::create([
            'type' => 'owner',
            'title' => 'Owner'
        ]);
        Role::create([
            'type' => 'admin',
            'title' => 'Admin'
        ]);
        Role::create([
            'type' => 'moderator',
            'title' => 'Moderator'
        ]);
    }

    /** @test */
    public function an_user_can_up_vote_a_post()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $this->post(route('posts.upvote', [$post->subPage->name, $post->id]));

        $this->assertEquals(1, $post->score());
    }

    /** @test */
    public function an_user_can_reset_up_vote_a_post()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $this->post(route('posts.upvote', [$post->subPage->name, $post->id]));

        $this->assertEquals(1, $post->score());

        $this->post(route('posts.upvote', [$post->subPage->name, $post->id]));
        $this->assertEquals(0, $post->score());
    }

    /** @test */
    public function an_user_can_down_vote_a_post()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $this->post(route('posts.downvote', [$post->subPage->name, $post->id]));

        $this->assertEquals(-1, $post->score());
    }

    /** @test */
    public function an_user_can_reset_down_vote_a_post()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $this->post(route('posts.downvote', [$post->subPage->name, $post->id]));

        $this->assertEquals(-1, $post->score());

        $this->post(route('posts.downvote', [$post->subPage->name, $post->id]));
        $this->assertEquals(0, $post->score());
    }

    /** @test */
    public function an_user_can_change_upvote_to_downvote_a_post()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $this->post(route('posts.upvote', [$post->subPage->name, $post->id]));

        $this->assertEquals(1, $post->score());

        $this->post(route('posts.downvote', [$post->subPage->name, $post->id]));
        $this->assertEquals(-1, $post->score());
    }

    /** @test */
    public function an_user_can_change_downvote_to_upvote_a_post()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $this->post(route('posts.downvote', [$post->subPage->name, $post->id]));

        $this->assertEquals(-1, $post->score());

        $this->post(route('posts.upvote', [$post->subPage->name, $post->id]));
        $this->assertEquals(1, $post->score());
    }

    /** @test */
    public function an_user_can_up_vote_a_comment()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->actingAs($user);

        $this->post(route('comments.upvote', [$comment->commentable->subPage->name, $comment->commentable->id, $comment->id]));

        $this->assertEquals(1, $comment->score());
    }

    /** @test */
    public function an_user_can_reset_up_vote_a_comment()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->actingAs($user);

        $this->post(route('comments.upvote', [$comment->commentable->subPage->name, $comment->commentable->id, $comment->id]));

        $this->assertEquals(1, $comment->score());

        $this->post(route('comments.upvote', [$comment->commentable->subPage->name, $comment->commentable->id, $comment->id]));
        $this->assertEquals(0, $comment->score());
    }

    /** @test */
    public function an_user_can_down_vote_a_comment()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->actingAs($user);

        $this->post(route('comments.downvote', [$comment->commentable->subPage->name, $comment->commentable->id, $comment->id]));

        $this->assertEquals(-1, $comment->score());
    }

    /** @test */
    public function an_user_can_reset_down_vote_a_comment()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->actingAs($user);

        $this->post(route('comments.downvote', [$comment->commentable->subPage->name, $comment->commentable->id, $comment->id]));

        $this->assertEquals(-1, $comment->score());

        $this->post(route('comments.downvote', [$comment->commentable->subPage->name, $comment->commentable->id, $comment->id]));
        $this->assertEquals(0, $comment->score());
    }

    /** @test */
    public function an_user_can_change_upvote_to_downvote_a_comment()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->actingAs($user);

        $this->post(route('comments.upvote', [$comment->commentable->subPage->name, $comment->commentable->id, $comment->id]));

        $this->assertEquals(1, $comment->score());

        $this->post(route('comments.downvote', [$comment->commentable->subPage->name, $comment->commentable->id, $comment->id]));
        $this->assertEquals(-1, $comment->score());
    }

    /** @test */
    public function an_user_can_change_downvote_to_upvote_a_comment()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->actingAs($user);

        $this->post(route('comments.downvote', [$comment->commentable->subPage->name, $comment->commentable->id, $comment->id]));

        $this->assertEquals(-1, $comment->score());

        $this->post(route('comments.upvote', [$comment->commentable->subPage->name, $comment->commentable->id, $comment->id]));
        $this->assertEquals(1, $comment->score());
    }

}

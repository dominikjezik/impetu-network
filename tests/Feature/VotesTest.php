<?php

namespace Tests\Feature;

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
        $subPage = SubPage::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $this->post("/r/$subPage->name/$post->id/upvote");

        $this->assertEquals(1, $post->score());
    }

    /** @test */
    public function an_user_can_reset_up_vote_a_post()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $subPage = SubPage::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $this->post("/r/$subPage->name/$post->id/upvote");

        $this->assertEquals(1, $post->score());

        $this->post("/r/$subPage->name/$post->id/upvote");
        $this->assertEquals(0, $post->score());
    }

    /** @test */
    public function an_user_can_down_vote_a_post()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $subPage = SubPage::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $this->post("/r/$subPage->name/$post->id/downvote");

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
    public function an_user_can_change_upvote_to_downvote()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $subPage = SubPage::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $this->post("/r/$subPage->name/$post->id/upvote");

        $this->assertEquals(1, $post->score());

        $this->post("/r/$subPage->name/$post->id/downvote");
        $this->assertEquals(-1, $post->score());
    }

    /** @test */
    public function an_user_can_change_downvote_to_upvote()
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


}

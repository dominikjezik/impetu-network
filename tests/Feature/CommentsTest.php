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

class CommentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_user_can_comment_a_post()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $subPage = SubPage::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $subPage->joinMember($user);

        $this->post("/r/$subPage->name/$post->id/comments", [
            'body' => 'Lorem ipsum dolor sit amet.'
        ]);

        $this->assertEquals(1, $post->comments->count());
    }

    /** @test */
    public function an_user_can_comment_a_comment()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $subPage = SubPage::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $subPage->joinMember($user);

        $commentOnPost = $post->comments()->create([
            'user_id' => auth()->id(),
            'body' => 'Commented post'
        ]);

        $this->post("/r/$subPage->name/$post->id/comments/$commentOnPost->id", [
            'body' => 'Lorem ipsum dolor sit amet.'
        ]);

        $this->assertEquals(1, $post->comments->count());
        $this->assertEquals(1, $commentOnPost->comments->count());
    }

}

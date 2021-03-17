<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\SubPage;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

class PostsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_title_of_post_is_required()
    {
        $user = User::factory()->create();
        $subPage = SubPage::factory()->create();

        $subPage->joinMember($user);

        $this->actingAs($user);

        $this->post("/r/$subPage->name/posts", [
            'title' => "",
            'body' => "Lorem ipsum dolor sit amet."
        ]);

        $this->assertEquals(0, $subPage->posts()->count());
    }

    /** @test */
    public function a_body_of_post_is_required()
    {
        $user = User::factory()->create();
        $subPage = SubPage::factory()->create();

        $subPage->joinMember($user);

        $this->actingAs($user);

        $this->post("/r/$subPage->name/posts", [
            'title' => "Hello world!",
            'body' => ""
        ]);

        $this->assertEquals(0, $subPage->posts()->count());
    }

    /** @test */
    public function only_member_can_publish_a_post_in_sub_page()
    {
        $user = User::factory()->create();
        $subPage = SubPage::factory()->create();

        $this->actingAs($user);

        $this->post("/r/$subPage->name/posts", [
            'title' => "Hello world!",
            'body' => "Lorem ipsum dolor sit amet."
        ]);

        $this->assertEquals(0, $subPage->posts()->count());

        $subPage->joinMember($user);

        $response = $this->post("/r/$subPage->name/posts", [
            'title' => "Hello world!",
            'body' => "Lorem ipsum dolor sit amet."
        ]);

        $this->assertEquals(1, $subPage->posts()->count());

        $response->assertRedirect();
    }

    /** @test */
    public function an_author_of_post_can_delete_his_post()
    {
        $this->withExceptionHandling();
        $post = Post::factory()->create();

        $this->actingAs($post->author);

        $this->delete("/r/{$post->subPage->name}/$post->id");

        $this->assertEquals(0, Post::count());
    }

    /** @test */
    public function an_user_cannot_delete_foreign_post()
    {
        $subPage = SubPage::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs(User::factory()->create());

        $this->delete("/r/$subPage->name/$post->id");

        $this->assertEquals(1, Post::count());
    }


}

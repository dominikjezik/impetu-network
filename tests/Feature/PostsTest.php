<?php

namespace Tests\Feature;

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


}

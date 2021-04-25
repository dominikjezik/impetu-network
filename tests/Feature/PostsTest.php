<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Role;
use App\Models\SubPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->setInitialRoles();
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
    public function a_title_of_post_is_required()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $subPage = SubPage::factory()->create();

        $this->post(route('posts.store', $subPage), [
            'title' => "",
            'body' => "Lorem ipsum dolor sit amet."
        ]);

        $this->assertEquals(0, $subPage->posts()->count());
    }

    /** @test */
    public function a_body_of_post_is_required()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $subPage = SubPage::factory()->create();

        $this->post(route('posts.store', $subPage), [
            'title' => "Hello world!",
            'body' => ""
        ]);

        $this->assertEquals(0, $subPage->posts()->count());
    }

    /** @test */
    public function only_member_can_publish_a_post_in_sub_page()
    {
        $this->actingAs(User::factory()->create());
        $subPage = SubPage::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('posts.store', $subPage), [
            'title' => "Hello world!",
            'body' => "Lorem ipsum dolor sit amet."
        ]);

        $this->assertEquals(0, $subPage->posts()->count());

        $subPage->joinMember($user);

        $response = $this->post(route('posts.store', $subPage), [
            'title' => "Hello world!",
            'body' => "Lorem ipsum dolor sit amet."
        ]);

        $this->assertEquals(1, $subPage->posts()->count());

        $response->assertRedirect();
    }

    /** @test */
    public function an_author_of_the_post_can_update_that_post()
    {
        $this->actingAs(User::factory()->create());
        $subPage = SubPage::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);
        $subPage->joinMember($user);

        $this->post(route('posts.store', $subPage), [
            'title' => "Hello world!",
            'body' => "Lorem ipsum dolor sit amet."
        ]);

        $post = Post::first();

        $updatedTitle = "Updated title";
        $updatedBody = "Updated body";

        $this->patch(route('posts.update', [$subPage, $post]), [
            'title' => $updatedTitle,
            'body' => $updatedBody
        ]);

        $post->refresh();

        $this->assertEquals($updatedTitle, $post->title);
        $this->assertEquals($updatedBody, $post->body);
    }

    /** @test */
    public function an_author_of_post_can_delete_his_post()
    {
        $this->withExceptionHandling();
        $this->actingAs(User::factory()->create());
        $post = Post::factory()->create();

        $this->actingAs($post->author);

        $this->delete(route('posts.destroy', [$post->subPage, $post]));

        $this->assertEquals(0, Post::count());
    }

    /** @test */
    public function an_user_cannot_delete_foreign_post()
    {
        $this->actingAs(User::factory()->create());

        $subPage = SubPage::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs(User::factory()->create());

        $this->delete(route('posts.destroy', [$subPage, $post]));

        $this->assertEquals(1, Post::count());
    }

    /** @test */
    public function an_moderator_of_sub_page_can_delete_member_post()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(User::factory()->create());
        $subPage = SubPage::factory()->create();
        $post = Post::factory()->create(['sub_page_id' => $subPage->id]);

        $moderator = User::factory()->create();
        $subPage->joinMember($moderator);
        $subPage->tryToSetRole('moderator', $moderator);
        $this->actingAs($moderator);

        $this->delete(route('posts.destroy', [$subPage, $post]));

        $this->assertEquals(0, Post::count());
    }

    /** @test */
    public function an_moderator_of_sub_page_one_can_not_delete_posts_of_sub_page_two()
    {
        $this->actingAs(User::factory()->create());
        $subPageOne = SubPage::factory()->create();
        $post = Post::factory()->create(['sub_page_id' => $subPageOne->id]);

        $subPageTwo = SubPage::factory()->create();
        $moderator = User::factory()->create();
        $subPageOne->joinMember($moderator);
        $subPageTwo->joinMember($moderator);
        $subPageTwo->tryToSetRole('moderator', $moderator);
        $this->actingAs($moderator);

        $this->delete(route('posts.destroy', [$subPageOne, $post]));

        $this->assertEquals(1, Post::count());
    }


}

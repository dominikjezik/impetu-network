<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Role;
use App\Models\SubPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SavedPostsTest extends TestCase
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
    public function an_auth_user_can_display_his_saved_posts_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('posts.save.index'));
        $response->assertOk();
    }

    /** @test */
    public function an_user_can_save_a_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $this->post(route('posts.save.store', [$post->subPage->name, $post->id]));
        $user->refresh();

        $this->assertCount(1, $user->savedPosts);
    }

    /** @test */
    public function an_user_can_remove_post_from_saved_posts()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);
        $post->saveForUser($user);

        $this->delete(route('posts.save.destroy', [$post->subPage->name, $post->id]));
        $user->refresh();

        $this->assertCount(0, $user->savedPosts);
    }

}

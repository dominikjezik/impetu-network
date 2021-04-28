<?php

namespace Tests\Unit;

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
    public function an_user_save_a_post()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create();

        $this->assertCount(0, $user->savedPosts);

        $post->saveForUser($user);
        $user->refresh();

        $this->assertCount(1, $user->savedPosts);
    }

    /** @test */
    public function an_user_remove_a_post_from_saves_posts()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create();

        $this->assertCount(0, $user->savedPosts);

        $post->saveForUser($user);
        $user->refresh();

        $this->assertCount(1, $user->savedPosts);

        $post->removeFromSaved($user);
        $user->refresh();

        $this->assertCount(0, $user->savedPosts);
    }

    /** @test */
    public function a_post_can_not_be_saved_more_times_by_one_user()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create();

        $this->assertCount(0, $user->savedPosts);

        $post->saveForUser($user);
        $post->saveForUser($user);
        $user->refresh();

        $this->assertCount(1, $user->savedPosts);
    }

}

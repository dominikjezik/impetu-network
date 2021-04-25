<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Role;
use App\Models\SubPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentsTest extends TestCase
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
    public function an_user_can_comment_a_post()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $post->subPage->joinMember($user);

        $this->post(route('comments.post.store', [$post->subPage->name, $post->id]), [
            'body' => 'Lorem ipsum dolor sit amet.'
        ]);

        $this->assertEquals(1, $post->comments->count());
    }

    /** @test */
    public function an_user_can_comment_a_comment()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $post->subPage->joinMember($user);

        $commentOnPost = $post->comments()->create([
            'user_id' => auth()->id(),
            'body' => 'Commented post'
        ]);

        $this->post(route('comments.comment.store', [$post->subPage->name, $post->id, $commentOnPost->id]), [
            'body' => 'Lorem ipsum dolor sit amet.'
        ]);

        $this->assertEquals(1, $post->comments->count());
        $this->assertEquals(1, $commentOnPost->comments->count());
    }

    /** @test */
    public function an_author_of_comment_can_delete_his_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = $post->comments()->create(['body' => 'test', 'user_id' => $user->id]);

        $this->assertEquals(1, $post->comments->count());

        $this->actingAs($user);

        $this->delete(route('comments.destroy', [$post->subPage->name, $post->id, $comment->id]));

        $post->load('comments');
        $this->assertEquals(0, $post->comments->count());
    }

    /** @test */
    public function an_user_cannot_delete_foreign_comment()
    {
        $subPage = SubPage::first();
        $comment = Comment::factory()->create();

        $this->actingAs(User::factory()->create());

        $this->delete(route('comments.destroy', [$subPage->name, $comment->commentable->id, $comment->id]));

        $this->assertEquals(1, Comment::count());
    }

    /** @test */
    public function an_author_of_comment_can_edit_his_comment()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = $post->comments()->create(['body' => 'test', 'user_id' => $user->id]);

        $this->assertEquals(1, $post->comments->count());

        $this->actingAs($user);

        $this->patch(route('comments.destroy', [$post->subPage->name, $post->id, $comment->id]), [
            'body' => 'updated!'
        ]);

        $this->assertEquals('updated!', $comment->fresh()->body);
    }

    /** @test */
    public function an_user_cannot_edit_foreign_comment()
    {
        $subPage = SubPage::first();
        $comment = Comment::factory()->create();

        $this->actingAs(User::factory()->create());

        $this->patch(route('comments.destroy', [$subPage->name, $comment->commentable->id, $comment->id]), [
            'body' => 'updated!'
        ]);

        $this->assertNotEquals('updated!', $comment->fresh()->body);
    }

    /** @test */
    public function an_moderator_can_delete_comment()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(User::factory()->create());
        $subPage = SubPage::factory()->create();
        $post = Post::factory()->create(['sub_page_id' => $subPage]);
        $comment = $post->comments()->create(['body' => 'test', 'user_id' => User::factory()->create()->id, 'sub_page_id' => $subPage->id]);

        $moderator = User::factory()->create();
        $subPage->joinMember($moderator);
        $subPage->tryToSetRole('moderator', $moderator);

        $this->assertEquals(1, $post->comments->count());

        $this->actingAs($moderator);

        $this->delete(route('comments.destroy', [$post->subPage->name, $post->id, $comment->id]));

        $post->load('comments');
        $this->assertEquals(0, $post->comments->count());
    }

}

<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\SubPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $post = Post::factory()->create(['sub_page_id' => $subPage->id]);

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
        $post = Post::factory()->create(['sub_page_id' => $subPage->id]);

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

    /** @test */
    public function an_author_of_comment_can_delete_his_comment()
    {
        $this->withExceptionHandling();

        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = $post->comments()->create(['body' => 'test', 'user_id' => $user->id]);

        $this->assertEquals(1, $post->comments->count());

        $this->actingAs($user);

        $this->delete("/r/{$post->subPage->name}/$post->id/comments/$comment->id");

        $post->load('comments');
        $this->assertEquals(0, $post->comments->count());
    }

    /** @test */
    public function an_user_cannot_delete_foreign_comment()
    {
        $subPage = SubPage::factory()->create();
        $comment = Comment::factory()->create();

        $this->actingAs(User::factory()->create());

        $this->delete("/r/$subPage->name/{$comment->commentable->id}/comments/$comment->id");

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

        $this->patch("/r/{$post->subPage->name}/$post->id/comments/$comment->id", [
            'body' => 'updated!'
        ]);

        $this->assertEquals('updated!', $comment->fresh()->body);
    }

    /** @test */
    public function an_user_cannot_edit_foreign_comment()
    {
        $subPage = SubPage::factory()->create();
        $comment = Comment::factory()->create();

        $this->actingAs(User::factory()->create());

        $this->patch("/r/$subPage->name/{$comment->commentable->id}/comments/$comment->id", [
            'body' => 'updated!'
        ]);

        $this->assertNotEquals('updated!', $comment->fresh()->body);
    }

}

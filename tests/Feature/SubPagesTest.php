<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\SubPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubPagesTest extends TestCase
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
    public function a_sub_page_screen_can_be_rendered()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(User::factory()->create());

        $subPage = SubPage::factory()->create();
        $response = $this->get(route('subpages.show', $subPage));

        $response->assertStatus(200);
    }

    /** @test */
    public function an_user_can_join_a_sub_page()
    {
        $this->withoutExceptionHandling();

        $owner = User::factory()->create();
        $this->actingAs($owner);

        $subPage = SubPage::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('subpages.join', $subPage));

        $response->assertRedirect(route('subpages.show', $subPage));

        $this->assertEquals(2, $subPage->members->count());
        $this->assertTrue($subPage->members->contains($user));
    }

    /** @test */
    public function an_user_can_leave_a_sub_page()
    {
        $this->withoutExceptionHandling();

        $owner = User::factory()->create();
        $this->actingAs($owner);
        $subPage = SubPage::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);
        $subPage->joinMember($user);

        $this->assertEquals(2, $subPage->members->count());
        $this->assertTrue($subPage->members->contains($user));

        $response = $this->post(route('subpages.leave', $subPage));
        $response->assertRedirect(route('subpages.show', $subPage));

        $subPage->load('members');

        $this->assertEquals(1, $subPage->members->count());
        $this->assertFalse($subPage->members->contains($user));
    }

    /** @test */
    public function only_auth_user_can_join_sub_page()
    {
        $this->actingAs(User::factory()->create());
        $subPage = SubPage::factory()->create();

        auth()->logout();

        $response = $this->post(route('subpages.join', $subPage));

        $this->assertCount(1, $subPage->members);

        $response->assertRedirect();
    }

    /** @test */
    public function only_auth_user_can_leave_sub_page()
    {
        $this->actingAs(User::factory()->create());
        $subPage = SubPage::factory()->create();

        auth()->logout();

        $response = $this->post(route('subpages.leave', $subPage));

        $this->assertCount(1, $subPage->members);

        $response->assertRedirect();
    }

    /** @test */
    public function auth_user_can_create_sub_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('subpages.store'), [
            'name' => 'test',
            'title' => 'Test community',
            'description' => 'Lorem ipsum dolor sit amet.'
        ]);

        $this->assertEquals(1, SubPage::count());
    }

    /** @test */
    public function guest_can_not_create_sub_page()
    {
        $this->post(route('subpages.store'), [
            'name' => 'test',
            'title' => 'Test community',
            'description' => 'Lorem ipsum dolor sit amet.'
        ]);

        $this->assertEquals(0, SubPage::count());
    }

    /** @test */
    public function name_is_required_to_create_a_new_sub_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('subpages.store'), [
            'name' => '',
            'title' => 'Test community',
            'description' => 'Lorem ipsum dolor sit amet.'
        ]);

        $this->assertEquals(0, SubPage::count());
    }

    /** @test */
    public function title_is_required_to_create_a_new_sub_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('subpages.store'), [
            'name' => 'test',
            'title' => '',
            'description' => 'Lorem ipsum dolor sit amet.'
        ]);

        $this->assertEquals(0, SubPage::count());
    }

    /** @test */
    public function description_is_required_to_create_a_new_sub_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('subpages.store'), [
            'name' => 'test',
            'title' => 'Test community',
            'description' => ''
        ]);

        $this->assertEquals(0, SubPage::count());
    }

    /** @test */
    public function users_with_a_role_can_view_the_manage_page()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(User::factory()->create());

        $subPage = SubPage::factory()->create();
        $response = $this->get(route('subpages.edit', $subPage));

        $response->assertStatus(200);
    }

    /** @test */
    public function users_without_a_role_can_not_view_the_manage_page()
    {
        $owner = User::factory()->create();
        $this->actingAs($owner);
        $subPage = SubPage::factory()->create();

        $this->actingAs(User::factory()->create());

        $response = $this->get(route('subpages.edit', $subPage));
        $response->assertStatus(403);
    }

    /** @test */
    public function a_basic_information_can_be_updated_by_owner()
    {
        $this->withoutExceptionHandling();
        $owner = User::factory()->create();
        $this->actingAs($owner);
        $subPage = SubPage::factory()->create();

        $this->patch(route('subpages.udpate', $subPage), [
            'title' => 'Updated title',
            'description' => 'Updated description'
        ]);

        $subPage->refresh();

        $this->assertEquals('Updated title', $subPage->title);
        $this->assertEquals('Updated description', $subPage->description);
    }


    /** @test */
    public function a_basic_information_can_be_updated_by_admin()
    {
        $owner = User::factory()->create();
        $this->actingAs($owner);
        $subPage = SubPage::factory()->create();

        $admin = User::factory()->create();
        $subPage->joinMember($admin);
        $subPage->tryToSetRole('admin', $admin);
        $this->actingAs($admin);

        $this->patch(route('subpages.udpate', $subPage), [
            'title' => 'Updated title',
            'description' => 'Updated description'
        ]);

        $subPage->refresh();

        $this->assertEquals('Updated title', $subPage->title);
        $this->assertEquals('Updated description', $subPage->description);
    }

    /** @test */
    public function a_basic_information_can_not_be_updated_by_moderator()
    {
        $owner = User::factory()->create();
        $this->actingAs($owner);
        $subPage = SubPage::factory()->create();

        $moderator = User::factory()->create();
        $subPage->joinMember($moderator);
        $subPage->tryToSetRole('moderator', $moderator);
        $this->actingAs($moderator);

        $originalTitle = $subPage->title;
        $originalDescription = $subPage->description;

        $this->patch(route('subpages.udpate', $subPage), [
            'title' => 'Updated title',
            'description' => 'Updated description'
        ]);

        $subPage->refresh();

        $this->assertEquals($originalTitle, $subPage->title);
        $this->assertEquals($originalDescription, $subPage->description);
    }

    /** @test */
    public function a_basic_member_can_not_update_basic_information()
    {
        $owner = User::factory()->create();
        $this->actingAs($owner);
        $subPage = SubPage::factory()->create();

        $user = User::factory()->create();
        $subPage->joinMember($user);
        $this->actingAs($user);

        $originalTitle = $subPage->title;
        $originalDescription = $subPage->description;

        $this->patch(route('subpages.udpate', $subPage), [
            'title' => 'Updated title',
            'description' => 'Updated description'
        ]);

        $this->assertEquals($originalTitle, $subPage->title);
        $this->assertEquals($originalDescription, $subPage->description);
    }

    /** @test */
    public function an_owner_of_sub_page_can_delete_sub_page()
    {
        $owner = User::factory()->create();
        $this->actingAs($owner);
        $subPage = SubPage::factory()->create();

        $this->delete(route('subpages.destroy', $subPage));

        $this->assertEmpty(SubPage::all());
    }

    /** @test */
    public function other_roles_of_sub_page_can_not_delete_sub_page()
    {
        $owner = User::factory()->create();
        $this->actingAs($owner);
        $subPage = SubPage::factory()->create();

        $admin = User::factory()->create();
        $subPage->joinMember($admin);
        $subPage->tryToSetRole('admin', $admin);
        $this->actingAs($admin);

        $this->delete(route('subpages.destroy', $subPage));

        $this->assertCount(1, SubPage::all());
    }

    /** @test */
    public function a_basic_member_can_not_delete_sub_page()
    {
        $owner = User::factory()->create();
        $this->actingAs($owner);
        $subPage = SubPage::factory()->create();

        $user = User::factory()->create();
        $subPage->joinMember($user);
        $this->actingAs($user);

        $this->delete(route('subpages.destroy', $subPage));

        $this->assertCount(1, SubPage::all());
    }

}

<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Role;
use App\Models\SubPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolesTest extends TestCase
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
    public function an_owner_of_sub_page_can_assign_admin_role_to_member()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(User::factory()->create());
        $subPage = SubPage::factory()->create();

        $newAdmin = User::factory()->create();
        $subPage->joinMember($newAdmin);

        $response = $this->post(route('roles.store', $subPage), [
            'user' => $newAdmin->id,
            'role' => 'admin'
        ]);

        $response->assertStatus(201);
        $this->assertTrue($subPage->isAdmin($newAdmin));
    }

    /** @test */
    public function an_owner_of_sub_page_can_assign_moderator_role_to_member()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(User::factory()->create());
        $subPage = SubPage::factory()->create();

        $newModerator = User::factory()->create();
        $subPage->joinMember($newModerator);

        $response = $this->post(route('roles.store', $subPage), [
            'user' => $newModerator->id,
            'role' => 'moderator'
        ]);

        $response->assertStatus(201);
        $this->assertTrue($subPage->isModerator($newModerator));
    }

    /** @test */
    public function an_admin_of_sub_page_can_assign_moderator_role_to_member()
    {
        $this->actingAs(User::factory()->create());
        $subPage = SubPage::factory()->create();

        $admin = User::factory()->create();
        $subPage->joinMember($admin);
        $subPage->tryToSetRole('admin', $admin);
        $this->actingAs($admin);

        $newModerator = User::factory()->create();
        $subPage->joinMember($newModerator);

        $response = $this->post(route('roles.store', $subPage), [
            'user' => $newModerator->id,
            'role' => 'moderator'
        ]);

        $response->assertStatus(201);
        $this->assertTrue($subPage->isModerator($newModerator));
    }

    /** @test */
    public function an_admin_of_sub_page_can_not_assign_admin_role_to_member()
    {
        $this->actingAs(User::factory()->create());
        $subPage = SubPage::factory()->create();

        $admin = User::factory()->create();
        $subPage->joinMember($admin);
        $subPage->tryToSetRole('admin', $admin);
        $this->actingAs($admin);

        $user = User::factory()->create();
        $subPage->joinMember($user);

        $response = $this->post(route('roles.store', $subPage), [
            'user' => $user->id,
            'role' => 'admin'
        ]);

        $response->assertStatus(403);
        $this->assertFalse($subPage->isAdmin($user));
    }

    /** @test */
    public function a_moderator_of_sub_page_can_not_assign_role_to_member()
    {
        $this->actingAs(User::factory()->create());
        $subPage = SubPage::factory()->create();

        $moderator = User::factory()->create();
        $subPage->joinMember($moderator);
        $subPage->tryToSetRole('moderator', $moderator);
        $this->actingAs($moderator);

        $user = User::factory()->create();
        $subPage->joinMember($user);

        $response = $this->post(route('roles.store', $subPage), [
            'user' => $user->id,
            'role' => 'moderator'
        ]);

        $response->assertStatus(403);
        $this->assertFalse($subPage->isModerator($user));
    }

    /** @test */
    public function role_can_not_be_assign_to_non_member()
    {
        $this->actingAs(User::factory()->create());
        $subPage = SubPage::factory()->create();

        $newModerator = User::factory()->create();

        $response = $this->post(route('roles.store', $subPage), [
            'user' => $newModerator->id,
            'role' => 'moderator'
        ]);

        $response->assertStatus(403);

        $this->assertFalse($subPage->isModerator($newModerator));
    }

    /** @test */
    public function an_owner_can_remove_role_of_admin()
    {
        $this->actingAs(User::factory()->create());
        $subPage = SubPage::factory()->create();

        $admin = User::factory()->create();
        $subPage->joinMember($admin);
        $subPage->tryToSetRole('admin', $admin);

        $this->assertTrue($subPage->isAdmin($admin));

        $this->delete(route('roles.destroySomeoneElseRole', $subPage), [
            'user' => $admin->id
        ]);

        $this->assertFalse($subPage->isAdmin($admin));
    }

    /** @test */
    public function an_admin_can_remove_role_of_moderator()
    {
        $this->actingAs(User::factory()->create());
        $subPage = SubPage::factory()->create();

        $admin = User::factory()->create();
        $subPage->joinMember($admin);
        $subPage->tryToSetRole('admin', $admin);

        $moderator = User::factory()->create();
        $subPage->joinMember($moderator);
        $subPage->tryToSetRole('moderator', $moderator);

        $this->actingAs($admin);

        $this->assertTrue($subPage->isModerator($moderator));

        $this->delete(route('roles.destroySomeoneElseRole', $subPage), [
            'user' => $moderator->id
        ]);

        $this->assertFalse($subPage->isModerator($moderator));
    }

    /** @test */
    public function an_admin_can_not_remove_role_of_admin()
    {
        $this->actingAs(User::factory()->create());
        $subPage = SubPage::factory()->create();

        $admin = User::factory()->create();
        $subPage->joinMember($admin);
        $subPage->tryToSetRole('admin', $admin);

        $admin2 = User::factory()->create();
        $subPage->joinMember($admin2);
        $subPage->tryToSetRole('admin', $admin2);

        $this->actingAs($admin);

        $this->assertTrue($subPage->isAdmin($admin2));

        $this->delete(route('roles.destroySomeoneElseRole', $subPage), [
            'user' => $admin2->id
        ]);

        $this->assertTrue($subPage->isAdmin($admin2));
    }

    /** @test */
    public function a_moderator_can_not_remove_role_of_admin()
    {
        $this->actingAs(User::factory()->create());
        $subPage = SubPage::factory()->create();

        $moderator = User::factory()->create();
        $subPage->joinMember($moderator);
        $subPage->tryToSetRole('moderator', $moderator);

        $admin = User::factory()->create();
        $subPage->joinMember($admin);
        $subPage->tryToSetRole('admin', $admin);

        $this->actingAs($moderator);

        $this->assertTrue($subPage->isAdmin($admin));

        $this->delete(route('roles.destroySomeoneElseRole', $subPage), [
            'user' => $admin->id
        ]);

        $this->assertTrue($subPage->isAdmin($admin));
    }

    /** @test */
    public function an_admin_of_sub_page_one_can_not_remove_role_of_moderator_sub_page_two()
    {
        $this->actingAs(User::factory()->create());
        $subPage1 = SubPage::factory()->create();
        $subPage2 = SubPage::factory()->create();

        $admin = User::factory()->create();
        $subPage1->joinMember($admin);
        $subPage1->tryToSetRole('admin', $admin);

        $moderator = User::factory()->create();
        $subPage2->joinMember($moderator);
        $subPage2->tryToSetRole('moderator', $moderator);

        $this->actingAs($admin);

        $this->assertTrue($subPage2->isModerator($moderator));

        $this->delete(route('roles.destroySomeoneElseRole', $subPage2), [
            'user' => $admin->id
        ]);

        $this->assertTrue($subPage2->isModerator($moderator));
    }

    /** @test */
    public function an_admin_can_give_up_a_role()
    {
        $this->actingAs(User::factory()->create());
        $subPage = SubPage::factory()->create();

        $admin = User::factory()->create();
        $subPage->joinMember($admin);
        $subPage->tryToSetRole('admin', $admin);

        $this->actingAs($admin);

        $this->assertTrue($subPage->isAdmin($admin));

        $this->delete(route('roles.destroy', $subPage));

        $this->assertFalse($subPage->isAdmin($admin));
    }

    /** @test */
    public function a_moderator_can_give_up_a_role()
    {
        $this->actingAs(User::factory()->create());
        $subPage = SubPage::factory()->create();

        $moderator = User::factory()->create();
        $subPage->joinMember($moderator);
        $subPage->tryToSetRole('moderator', $moderator);

        $this->actingAs($moderator);

        $this->assertTrue($subPage->isModerator($moderator));

        $this->delete(route('roles.destroy', $subPage));

        $this->assertFalse($subPage->isModerator($moderator));
    }

    /** @test */
    public function an_owner_can_not_directly_give_up_a_role()
    {
        $this->actingAs(User::factory()->create());
        $subPage = SubPage::factory()->create();

        $this->assertTrue($subPage->isOwner(auth()->user()));

        $this->delete(route('roles.destroy', $subPage));

        $this->assertTrue($subPage->isOwner(auth()->user()));
    }

    /** @test */
    public function if_user_with_role_leaves_sub_page_role_should_be_removed()
    {
        $this->actingAs(User::factory()->create());
        $subPage = SubPage::factory()->create();

        $admin = User::factory()->create();
        $subPage->joinMember($admin);
        $subPage->tryToSetRole('admin', $admin);

        $this->actingAs($admin);

        $this->assertTrue($subPage->isAdmin($admin));

        $this->post(route('subpages.leave', $subPage));

        $this->assertFalse($subPage->isAdmin($admin));
    }

}

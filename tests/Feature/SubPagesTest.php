<?php

namespace Tests\Feature;

use App\Models\SubPage;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

class SubPagesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_sub_page_screen_can_be_rendered()
    {
        $this->withoutExceptionHandling();
        $subPage = SubPage::factory()->create();
        $response = $this->get("/r/$subPage->name");

        $response->assertStatus(200);
    }

    /** @test */
    public function an_user_can_join_a_sub_page()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $subPage = SubPage::factory()->create();

        $response = $this->post("/r/$subPage->name/join");

        $response->assertRedirect("/r/$subPage->name");

        $this->assertEquals(1, $subPage->members->count());
        $this->assertTrue($subPage->members->contains($user));
    }

    /** @test */
    public function an_user_can_leave_a_sub_page()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $subPage = SubPage::factory()->create();
        $subPage->joinMember($user);

        $this->assertEquals(1, $subPage->members->count());
        $this->assertTrue($subPage->members->contains($user));

        $response = $this->post("/r/$subPage->name/leave");
        $response->assertRedirect("/r/$subPage->name");

        $subPage->load('members');

        $this->assertEquals(0, $subPage->members->count());
        $this->assertFalse($subPage->members->contains($user));
    }

    /** @test */
    public function only_auth_user_can_join_sub_page()
    {
        $subPage = SubPage::factory()->create();
        $response = $this->post("/r/$subPage->name/join");

        $this->assertCount(0, $subPage->members);

        $response->assertRedirect();
    }

    /** @test */
    public function only_auth_user_can_leave_sub_page()
    {
        $subPage = SubPage::factory()->create();
        $response = $this->post("/r/$subPage->name/leave");

        $this->assertCount(0, $subPage->members);

        $response->assertRedirect();
    }


//
//    public function test_new_users_can_register()
//    {
//        $response = $this->post('/register', [
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//            'password' => 'password',
//            'password_confirmation' => 'password',
//            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
//        ]);
//
//        $this->assertAuthenticated();
//        $response->assertRedirect(RouteServiceProvider::HOME);
//    }
}

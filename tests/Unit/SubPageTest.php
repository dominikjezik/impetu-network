<?php

namespace Tests\Unit;

use App\Models\SubPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_user_can_became_member_of_sub_page()
    {
        $user = User::factory()->create();
        $subPage = SubPage::factory()->create();

        $subPage->joinMember($user);

        $this->assertEquals(1, $subPage->members->count());
        $this->assertTrue($subPage->members->contains($user));
    }

    /** @test */
    public function an_user_may_be_removed_from_members_of_sub_page()
    {
        $user = User::factory()->create();
        $subPage = SubPage::factory()->create();

        $subPage->joinMember($user);

        $this->assertEquals(1, $subPage->members->count());
        $this->assertTrue($subPage->members->contains($user));

        $subPage->leaveMember($user);

        $subPage->load('members');

        $this->assertEquals(0, $subPage->members->count());
    }
}

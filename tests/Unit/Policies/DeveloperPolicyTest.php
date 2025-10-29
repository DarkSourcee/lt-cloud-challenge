<?php

namespace Tests\Unit\Policies;

use Tests\TestCase;
use App\Models\Developer;
use App\Models\User;
use App\Policies\DeveloperPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeveloperPolicyTest extends TestCase
{
    use RefreshDatabase;

    private DeveloperPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new DeveloperPolicy();
    }

    /** @test */
    public function any_user_can_view_any_developers()
    {
        $user = User::factory()->create();

        $this->assertTrue($this->policy->viewAny($user));
    }

    /** @test */
    public function user_can_view_own_developer()
    {
        $user = User::factory()->create();
        $developer = Developer::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->view($user, $developer));
    }

    /** @test */
    public function user_cannot_view_other_users_developer()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $developer = Developer::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->view($user, $developer));
    }

    /** @test */
    public function any_user_can_create_developer()
    {
        $user = User::factory()->create();

        $this->assertTrue($this->policy->create($user));
    }

    /** @test */
    public function user_can_update_own_developer()
    {
        $user = User::factory()->create();
        $developer = Developer::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->update($user, $developer));
    }

    /** @test */
    public function user_cannot_update_other_users_developer()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $developer = Developer::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->update($user, $developer));
    }

    /** @test */
    public function user_can_delete_own_developer()
    {
        $user = User::factory()->create();
        $developer = Developer::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->delete($user, $developer));
    }

    /** @test */
    public function user_cannot_delete_other_users_developer()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $developer = Developer::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->delete($user, $developer));
    }
}


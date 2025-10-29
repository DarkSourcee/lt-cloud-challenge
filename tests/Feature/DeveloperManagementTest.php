<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Developer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\Developers\Index;
use App\Livewire\Developers\Create;
use App\Livewire\Developers\Edit;

class DeveloperManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_view_developers_index_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('developers.index'));

        $response->assertOk();
        $response->assertSeeLivewire(Index::class);
    }

    /** @test */
    public function guest_cannot_access_developers_index()
    {
        $response = $this->get(route('developers.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_user_can_view_create_developer_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('developers.create'));

        $response->assertOk();
        $response->assertSeeLivewire(Create::class);
    }

    /** @test */
    public function user_can_create_developer()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('seniority', 'Sr')
            ->set('skills', ['PHP', 'Laravel', 'Vue.js'])
            ->call('save');

        $this->assertDatabaseHas('developers', [
            'user_id' => $user->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'seniority' => 'Sr',
        ]);
    }

    /** @test */
    public function name_is_required_when_creating_developer()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('name', '')
            ->set('email', 'john@example.com')
            ->set('seniority', 'Sr')
            ->call('save')
            ->assertHasErrors(['name' => 'required']);
    }

    /** @test */
    public function email_is_required_when_creating_developer()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('name', 'John Doe')
            ->set('email', '')
            ->set('seniority', 'Sr')
            ->call('save')
            ->assertHasErrors(['email' => 'required']);
    }

    /** @test */
    public function email_must_be_valid()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('name', 'John Doe')
            ->set('email', 'invalid-email')
            ->set('seniority', 'Sr')
            ->call('save')
            ->assertHasErrors(['email' => 'email']);
    }

    /** @test */
    public function seniority_is_required_when_creating_developer()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('seniority', '')
            ->call('save')
            ->assertHasErrors(['seniority' => 'required']);
    }

    /** @test */
    public function user_can_view_edit_developer_page_for_own_developer()
    {
        $user = User::factory()->create();
        $developer = Developer::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('developers.edit', $developer));

        $response->assertOk();
        $response->assertSeeLivewire(Edit::class);
    }

    /** @test */
    public function user_cannot_view_edit_page_for_other_users_developer()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $developer = Developer::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get(route('developers.edit', $developer));

        $response->assertForbidden();
    }

    /** @test */
    public function user_can_update_own_developer()
    {
        $user = User::factory()->create();
        $developer = Developer::factory()->create([
            'user_id' => $user->id,
            'name' => 'Old Name',
        ]);

        $this->actingAs($user);

        Livewire::test(Edit::class, ['developer' => $developer])
            ->set('name', 'New Name')
            ->set('email', 'newemail@example.com')
            ->set('seniority', 'Pl')
            ->call('save');

        $this->assertDatabaseHas('developers', [
            'id' => $developer->id,
            'name' => 'New Name',
            'email' => 'newemail@example.com',
            'seniority' => 'Pl',
        ]);
    }

    /** @test */
    public function user_can_delete_own_developer()
    {
        $user = User::factory()->create();
        $developer = Developer::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->call('delete', $developer->id);

        $this->assertDatabaseMissing('developers', [
            'id' => $developer->id,
        ]);
    }

    /** @test */
    public function user_can_see_only_their_developers()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $myDeveloper = Developer::factory()->create(['user_id' => $user->id, 'name' => 'My Developer']);
        $otherDeveloper = Developer::factory()->create(['user_id' => $otherUser->id, 'name' => 'Other Developer']);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->assertSee('My Developer')
            ->assertDontSee('Other Developer');
    }

    /** @test */
    public function user_can_search_developers_by_name()
    {
        $user = User::factory()->create();
        Developer::factory()->create(['user_id' => $user->id, 'name' => 'John Doe']);
        Developer::factory()->create(['user_id' => $user->id, 'name' => 'Jane Smith']);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->set('search', 'John')
            ->assertSee('John Doe')
            ->assertDontSee('Jane Smith');
    }

    /** @test */
    public function user_can_filter_developers_by_seniority()
    {
        $user = User::factory()->create();
        Developer::factory()->create(['user_id' => $user->id, 'name' => 'Senior Dev', 'seniority' => 'Sr']);
        Developer::factory()->create(['user_id' => $user->id, 'name' => 'Junior Dev', 'seniority' => 'Jr']);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->set('filterSeniority', 'Sr')
            ->assertSee('Senior Dev')
            ->assertDontSee('Junior Dev');
    }
}


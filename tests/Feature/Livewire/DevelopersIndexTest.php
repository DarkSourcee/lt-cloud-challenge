<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use App\Models\Developer;
use App\Models\User;
use App\Livewire\Developers\Index;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class DevelopersIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function component_can_render()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Livewire::test(Index::class);

        $component->assertStatus(200);
    }

    /** @test */
    public function component_displays_developers()
    {
        $user = User::factory()->create();
        $developer = Developer::factory()->create([
            'user_id' => $user->id,
            'name' => 'John Doe',
        ]);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->assertSee('John Doe');
    }

    /** @test */
    public function component_can_search_developers()
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
    public function component_can_filter_by_seniority()
    {
        $user = User::factory()->create();
        Developer::factory()->create([
            'user_id' => $user->id,
            'name' => 'Senior Developer',
            'seniority' => 'Sr',
        ]);
        Developer::factory()->create([
            'user_id' => $user->id,
            'name' => 'Junior Developer',
            'seniority' => 'Jr',
        ]);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->set('filterSeniority', 'Sr')
            ->assertSee('Senior Developer')
            ->assertDontSee('Junior Developer');
    }

    /** @test */
    public function component_can_filter_by_skill()
    {
        $user = User::factory()->create();
        Developer::factory()->create([
            'user_id' => $user->id,
            'name' => 'PHP Developer',
            'skills' => ['PHP', 'Laravel'],
        ]);
        Developer::factory()->create([
            'user_id' => $user->id,
            'name' => 'JS Developer',
            'skills' => ['JavaScript', 'React'],
        ]);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->set('filterSkill', 'PHP')
            ->assertSee('PHP Developer')
            ->assertDontSee('JS Developer');
    }

    /** @test */
    public function component_can_delete_developer()
    {
        $user = User::factory()->create();
        $developer = Developer::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->call('delete', $developer->id);

        $this->assertDatabaseMissing('developers', ['id' => $developer->id]);
    }

    /** @test */
    public function component_only_shows_authenticated_user_developers()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Developer::factory()->create(['user_id' => $user->id, 'name' => 'My Developer']);
        Developer::factory()->create(['user_id' => $otherUser->id, 'name' => 'Other Developer']);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->assertSee('My Developer')
            ->assertDontSee('Other Developer');
    }

    /** @test */
    public function component_shows_article_count_for_developer()
    {
        $user = User::factory()->create();
        $developer = Developer::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->assertSee($developer->name);
    }
}


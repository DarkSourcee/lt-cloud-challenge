<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use App\Models\User;
use App\Livewire\Developers\Create;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class DevelopersCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function component_can_render()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Livewire::test(Create::class);

        $component->assertStatus(200);
    }

    /** @test */
    public function component_can_save_developer()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('seniority', 'Sr')
            ->set('skills', ['PHP', 'Laravel'])
            ->call('save')
            ->assertRedirect(route('developers.index'));

        $this->assertDatabaseHas('developers', [
            'user_id' => $user->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'seniority' => 'Sr',
        ]);
    }

    /** @test */
    public function name_field_is_required()
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
    public function email_field_is_required()
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
    public function email_must_be_valid_format()
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
    public function seniority_field_is_required()
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
    public function seniority_must_be_valid_option()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('seniority', 'Invalid')
            ->call('save')
            ->assertHasErrors(['seniority']);
    }

    /** @test */
    public function component_can_add_skills()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('seniority', 'Sr')
            ->set('skills', ['PHP', 'Laravel', 'Vue.js'])
            ->call('save');

        $developer = \App\Models\Developer::where('email', 'john@example.com')->first();

        $this->assertNotNull($developer);
        $this->assertIsArray($developer->skills);
        $this->assertContains('PHP', $developer->skills);
        $this->assertContains('Laravel', $developer->skills);
        $this->assertContains('Vue.js', $developer->skills);
    }
}


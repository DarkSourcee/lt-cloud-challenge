<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use App\Models\Article;
use App\Models\User;
use App\Livewire\Articles\Index;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class ArticlesIndexTest extends TestCase
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
    public function component_displays_articles()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'user_id' => $user->id,
            'title' => 'My Test Article',
        ]);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->assertSee('My Test Article');
    }

    /** @test */
    public function component_can_delete_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->call('delete', $article->id);

        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }

    /** @test */
    public function component_only_shows_authenticated_user_articles()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Article::factory()->create(['user_id' => $user->id, 'title' => 'My Article']);
        Article::factory()->create(['user_id' => $otherUser->id, 'title' => 'Other Article']);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->assertSee('My Article')
            ->assertDontSee('Other Article');
    }

    /** @test */
    public function component_shows_developer_count_for_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->assertSee($article->title);
    }

    /** @test */
    public function component_displays_published_articles()
    {
        $user = User::factory()->create();
        Article::factory()->create([
            'user_id' => $user->id,
            'title' => 'Published Article',
            'published_at' => now(),
        ]);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->assertSee('Published Article');
    }

    /** @test */
    public function component_displays_draft_articles()
    {
        $user = User::factory()->create();
        Article::factory()->create([
            'user_id' => $user->id,
            'title' => 'Draft Article',
            'published_at' => null,
        ]);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->assertSee('Draft Article');
    }
}


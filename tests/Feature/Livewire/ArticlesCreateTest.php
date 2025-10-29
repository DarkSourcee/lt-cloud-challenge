<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use App\Models\Developer;
use App\Models\User;
use App\Livewire\Articles\Create;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class ArticlesCreateTest extends TestCase
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
    public function component_can_save_article()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('title', 'My Test Article')
            ->set('slug', 'my-test-article')
            ->set('content', 'This is the article content.')
            ->call('save')
            ->assertRedirect(route('articles.index'));

        $this->assertDatabaseHas('articles', [
            'user_id' => $user->id,
            'title' => 'My Test Article',
            'slug' => 'my-test-article',
        ]);
    }

    /** @test */
    public function title_field_is_required()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('title', '')
            ->set('content', 'Content here')
            ->call('save')
            ->assertHasErrors(['title' => 'required']);
    }

    /** @test */
    public function content_field_is_required()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('title', 'Test Article')
            ->set('content', '')
            ->call('save')
            ->assertHasErrors(['content' => 'required']);
    }

    /** @test */
    public function slug_is_generated_automatically_if_not_provided()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('title', 'My Test Article')
            ->set('slug', '')
            ->set('content', 'Content')
            ->call('save');

        $this->assertDatabaseHas('articles', [
            'title' => 'My Test Article',
        ]);
    }

    /** @test */
    public function component_can_attach_developers_to_article()
    {
        $user = User::factory()->create();
        $developer1 = Developer::factory()->create(['user_id' => $user->id]);
        $developer2 = Developer::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('title', 'Article with Developers')
            ->set('slug', 'article-with-developers')
            ->set('content', 'Content')
            ->set('selectedDevelopers', [$developer1->id, $developer2->id])
            ->call('save');

        $article = \App\Models\Article::where('title', 'Article with Developers')->first();

        $this->assertNotNull($article);
        $this->assertCount(2, $article->developers);
    }

    /** @test */
    public function component_can_set_published_date()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $publishedDate = now()->format('Y-m-d');

        Livewire::test(Create::class)
            ->set('title', 'Published Article')
            ->set('slug', 'published-article')
            ->set('content', 'Content')
            ->set('published_at', $publishedDate)
            ->call('save');

        $article = \App\Models\Article::where('title', 'Published Article')->first();

        $this->assertNotNull($article->published_at);
    }

    /** @test */
    public function article_can_be_saved_as_draft()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('title', 'Draft Article')
            ->set('slug', 'draft-article')
            ->set('content', 'Content')
            ->set('published_at', null)
            ->call('save');

        $article = \App\Models\Article::where('title', 'Draft Article')->first();

        $this->assertNull($article->published_at);
    }
}


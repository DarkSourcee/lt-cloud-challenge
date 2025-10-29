<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use App\Models\Developer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use App\Livewire\Articles\Index;
use App\Livewire\Articles\Create;
use App\Livewire\Articles\Edit;

class ArticleManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_view_articles_index_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('articles.index'));

        $response->assertOk();
        $response->assertSeeLivewire(Index::class);
    }

    /** @test */
    public function guest_cannot_access_articles_index()
    {
        $response = $this->get(route('articles.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_user_can_view_create_article_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('articles.create'));

        $response->assertOk();
        $response->assertSeeLivewire(Create::class);
    }

    /** @test */
    public function user_can_create_article()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('title', 'My Test Article')
            ->set('slug', 'my-test-article')
            ->set('content', 'This is the article content.')
            ->set('published_at', '2025-10-29')
            ->call('save');

        $this->assertDatabaseHas('articles', [
            'user_id' => $user->id,
            'title' => 'My Test Article',
            'slug' => 'my-test-article',
        ]);
    }

    /** @test */
    public function title_is_required_when_creating_article()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('title', '')
            ->set('content', 'Content')
            ->call('save')
            ->assertHasErrors(['title' => 'required']);
    }

    /** @test */
    public function content_is_required_when_creating_article()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('title', 'Title')
            ->set('content', '')
            ->call('save')
            ->assertHasErrors(['content' => 'required']);
    }

    /** @test */
    public function user_can_view_edit_article_page_for_own_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('articles.edit', $article));

        $response->assertOk();
        $response->assertSeeLivewire(Edit::class);
    }

    /** @test */
    public function user_cannot_view_edit_page_for_other_users_article()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get(route('articles.edit', $article));

        $response->assertForbidden();
    }

    /** @test */
    public function user_can_update_own_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'user_id' => $user->id,
            'title' => 'Old Title',
        ]);

        $this->actingAs($user);

        Livewire::test(Edit::class, ['article' => $article])
            ->set('title', 'New Title')
            ->set('slug', 'new-title')
            ->set('content', 'New content')
            ->call('save');

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'New Title',
            'slug' => 'new-title',
        ]);
    }

    /** @test */
    public function user_can_delete_own_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->call('delete', $article->id);

        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
        ]);
    }

    /** @test */
    public function user_can_see_only_their_articles()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $myArticle = Article::factory()->create(['user_id' => $user->id, 'title' => 'My Article']);
        $otherArticle = Article::factory()->create(['user_id' => $otherUser->id, 'title' => 'Other Article']);

        $this->actingAs($user);

        Livewire::test(Index::class)
            ->assertSee('My Article')
            ->assertDontSee('Other Article');
    }

    /** @test */
    public function user_can_attach_developers_to_article()
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

        $article = Article::where('title', 'Article with Developers')->first();

        $this->assertCount(2, $article->developers);
        $this->assertTrue($article->developers->contains($developer1));
        $this->assertTrue($article->developers->contains($developer2));
    }

    /** @test */
    public function article_can_be_published()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Create::class)
            ->set('title', 'Published Article')
            ->set('slug', 'published-article')
            ->set('content', 'Content')
            ->set('published_at', now()->format('Y-m-d'))
            ->call('save');

        $article = Article::where('title', 'Published Article')->first();

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

        $article = Article::where('title', 'Draft Article')->first();

        $this->assertNull($article->published_at);
    }
}


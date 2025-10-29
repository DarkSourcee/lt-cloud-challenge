<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Article;
use App\Models\User;
use App\Models\Developer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $article->user);
        $this->assertEquals($user->id, $article->user->id);
    }

    /** @test */
    public function it_can_have_many_developers()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);
        $developers = Developer::factory()->count(3)->create(['user_id' => $user->id]);
        
        $article->developers()->attach($developers->pluck('id'));

        $this->assertCount(3, $article->developers);
        $this->assertInstanceOf(Developer::class, $article->developers->first());
    }

    /** @test */
    public function it_casts_published_at_as_date()
    {
        $article = Article::factory()->create(['published_at' => '2025-10-29']);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $article->published_at);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = ['user_id', 'title', 'slug', 'content', 'cover_image', 'published_at'];
        $article = new Article();

        $this->assertEquals($fillable, $article->getFillable());
    }

    /** @test */
    public function it_can_be_published()
    {
        $article = Article::factory()->create(['published_at' => now()]);

        $this->assertNotNull($article->published_at);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $article->published_at);
    }

    /** @test */
    public function it_can_be_a_draft()
    {
        $article = Article::factory()->create(['published_at' => null]);

        $this->assertNull($article->published_at);
    }

    /** @test */
    public function it_has_a_slug()
    {
        $article = Article::factory()->create([
            'title' => 'My Test Article',
            'slug' => 'my-test-article',
        ]);

        $this->assertEquals('my-test-article', $article->slug);
    }

    /** @test */
    public function it_can_have_cover_image()
    {
        $article = Article::factory()->create(['cover_image' => 'images/cover.jpg']);

        $this->assertEquals('images/cover.jpg', $article->cover_image);
    }
}


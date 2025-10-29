<?php

namespace Tests\Unit\Policies;

use Tests\TestCase;
use App\Models\Article;
use App\Models\User;
use App\Policies\ArticlePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticlePolicyTest extends TestCase
{
    use RefreshDatabase;

    private ArticlePolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new ArticlePolicy();
    }

    /** @test */
    public function any_user_can_view_any_articles()
    {
        $user = User::factory()->create();

        $this->assertTrue($this->policy->viewAny($user));
    }

    /** @test */
    public function user_can_view_own_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->view($user, $article));
    }

    /** @test */
    public function user_cannot_view_other_users_article()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->view($user, $article));
    }

    /** @test */
    public function any_user_can_create_article()
    {
        $user = User::factory()->create();

        $this->assertTrue($this->policy->create($user));
    }

    /** @test */
    public function user_can_update_own_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->update($user, $article));
    }

    /** @test */
    public function user_cannot_update_other_users_article()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->update($user, $article));
    }

    /** @test */
    public function user_can_delete_own_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->delete($user, $article));
    }

    /** @test */
    public function user_cannot_delete_other_users_article()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->delete($user, $article));
    }
}


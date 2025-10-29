<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Developer;
use App\Models\User;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeveloperTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $developer = Developer::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $developer->user);
        $this->assertEquals($user->id, $developer->user->id);
    }

    /** @test */
    public function it_can_have_many_articles()
    {
        $developer = Developer::factory()->create();
        $articles = Article::factory()->count(3)->create(['user_id' => $developer->user_id]);
        
        $developer->articles()->attach($articles->pluck('id'));

        $this->assertCount(3, $developer->articles);
        $this->assertInstanceOf(Article::class, $developer->articles->first());
    }

    /** @test */
    public function it_casts_skills_as_array()
    {
        $skills = ['PHP', 'Laravel', 'Vue.js'];
        $developer = Developer::factory()->create(['skills' => $skills]);

        $this->assertIsArray($developer->skills);
        $this->assertEquals($skills, $developer->skills);
    }

    /** @test */
    public function it_has_fillable_attributes()
    {
        $fillable = ['user_id', 'name', 'email', 'seniority', 'skills'];
        $developer = new Developer();

        $this->assertEquals($fillable, $developer->getFillable());
    }

    /** @test */
    public function it_can_create_developer_with_all_seniority_levels()
    {
        $seniorityLevels = ['Jr', 'Pl', 'Sr'];

        foreach ($seniorityLevels as $level) {
            $developer = Developer::factory()->create(['seniority' => $level]);
            $this->assertEquals($level, $developer->seniority);
        }
    }

    /** @test */
    public function it_has_unique_email_per_user()
    {
        $user = User::factory()->create();
        $email = 'unique@example.com';
        
        Developer::factory()->create([
            'user_id' => $user->id,
            'email' => $email,
        ]);

        // Different user can use same email
        $otherUser = User::factory()->create();
        $developer = Developer::factory()->create([
            'user_id' => $otherUser->id,
            'email' => $email,
        ]);

        $this->assertEquals($email, $developer->email);
    }
}


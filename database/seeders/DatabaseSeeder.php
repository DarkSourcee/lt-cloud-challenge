<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create demo user
        $demoUser = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@ltcloud.com',
            'password' => bcrypt('password'),
        ]);

        // Create 10 additional users
        $users = User::factory(10)->create();
        $allUsers = $users->push($demoUser);

        // Create developers for each user
        foreach ($allUsers as $user) {
            \App\Models\Developer::factory(rand(2, 5))->create([
                'user_id' => $user->id,
            ]);
        }

        // Create articles for each user
        foreach ($allUsers as $user) {
            $articles = \App\Models\Article::factory(rand(3, 8))->create([
                'user_id' => $user->id,
            ]);

            // Attach random developers to each article (from the same user)
            foreach ($articles as $article) {
                $userDevelopers = $user->developers->pluck('id')->toArray();
                if (count($userDevelopers) > 0) {
                    $article->developers()->attach(
                        array_rand(array_flip($userDevelopers), min(rand(1, 3), count($userDevelopers)))
                    );
                }
            }
        }
    }
}

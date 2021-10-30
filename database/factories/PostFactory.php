<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $created = $this->withFaker()->dateTimeThisYear(now()->subMonth());
        $author = User::query()->inRandomOrder()->first()->id;

        return [
            'name' => $this->withFaker()->sentence(),
            'content' => $this->withFaker()->realText(1000),
            'author_id' => $author,
            'moderator_id' => optional(User::query()->firstWhere([
                'username' => 'admin',
            ]) ?? null, function (User $admin) {
                return $admin->id;
            }),
            'moderated_at' => $this->withFaker()->dateTimeThisYear($created)->getTimestamp(),
            'created_at' => $created->getTimestamp(),
        ];
    }
}

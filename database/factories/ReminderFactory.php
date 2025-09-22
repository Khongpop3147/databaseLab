<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ReminderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'   => User::inRandomOrder()->value('id') ?? User::factory(),
            'title'     => fake()->sentence(3),
            'notes'     => fake()->optional()->paragraph(),
            'remind_at' => fake()->optional()->dateTimeBetween('now', '+14 days'),
            'status'    => fake()->randomElement(['new','done']),
        ];
    }
}

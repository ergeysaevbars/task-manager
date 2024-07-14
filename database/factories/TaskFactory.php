<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use PharIo\Manifest\Author;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $needCreateAuthor = $this->faker->boolean();
        $needCreateAssignee = $this->faker->boolean();

        return [
            'title'       => $this->faker->word(),
            'description' => $this->faker->text(),
            'deadline'    => now()->addDays(random_int(-5, 31)),
            'status'      => $this->faker->randomElement(TaskStatus::cases()),
            'author_id'   => $needCreateAuthor ? User::factory()->create()->id : User::query()->inRandomOrder()->first()->id,
            'assigned_to' => $needCreateAssignee ?  User::factory()->create()->id : User::query()->inRandomOrder()->first()->id,
        ];
    }
}

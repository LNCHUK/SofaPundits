<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid(),
            'key' => Str::random(8),
            'name' => $this->faker->company(),
        ];
    }

    /**
     * Creates a group with no 'uuid' set (primarily to test the auto-generation).
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withNoUuid()
    {
        return $this->state(function (array $attributes) {
            return [
                'uuid' => null,
            ];
        });
    }

    /**
     * Creates a group with no 'key' set (primarily to test the auto-generation).
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withNoKey()
    {
        return $this->state(function (array $attributes) {
            return [
                'key' => null,
            ];
        });
    }
}

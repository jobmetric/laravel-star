<?php

namespace JobMetric\Star\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JobMetric\Star\Models\Star;

/**
 * @extends Factory<Star>
 */
class StarFactory extends Factory
{
    protected $model = Star::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'starable_id' => null,
            'starable_type' => null,
            'star' => $this->faker->numberBetween(1, 5)
        ];
    }

    /**
     * set user id
     *
     * @param int $user_id
     *
     * @return static
     */
    public function setUserId(int $user_id): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => $user_id
        ]);
    }

    /**
     * set starable
     *
     * @param int $starable_id
     * @param string $starable_type
     *
     * @return static
     */
    public function setStarable(int $starable_id, string $starable_type): static
    {
        return $this->state(fn(array $attributes) => [
            'starable_id' => $starable_id,
            'starable_type' => $starable_type
        ]);
    }

    /**
     * set star
     *
     * @param int $star
     *
     * @return static
     */
    public function setStar(int $star = 3): static
    {
        return $this->state(fn(array $attributes) => [
            'star' => $star
        ]);
    }
}

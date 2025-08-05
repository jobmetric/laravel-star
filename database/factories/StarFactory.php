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
            'starred_by_type' => null,
            'starred_by_id' => null,
            'starable_type' => null,
            'starable_id' => null,
            'rate' => $this->faker->numberBetween(config('star.min_rate'), config('star.max_rate')),
            'ip' => $this->faker->ipv4(),
            'device_id' => $this->faker->uuid(),
            'source' => $this->faker->randomElement(['web', 'mobile', 'api']),
        ];
    }

    /**
     * set starred by
     *
     * @param string $starred_by_type
     * @param int $starred_by_id
     *
     * @return static
     */
    public function setStarredBy(string $starred_by_type, int $starred_by_id): static
    {
        return $this->state(fn(array $attributes) => [
            'starred_by_type' => $starred_by_type,
            'starred_by_id' => $starred_by_id
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
     * set rate
     *
     * @param int $rate
     *
     * @return static
     */
    public function setRate(int $rate): static
    {
        return $this->state(fn(array $attributes) => [
            'rate' => $rate
        ]);
    }

    /**
     * set ip
     *
     * @param string $ip
     *
     * @return static
     */
    public function setIp(string $ip): static
    {
        return $this->state(fn(array $attributes) => [
            'ip' => $ip
        ]);
    }

    /**
     * set device id
     *
     * @param string $device_id
     *
     * @return static
     */
    public function setDeviceId(string $device_id): static
    {
        return $this->state(fn(array $attributes) => [
            'device_id' => $device_id
        ]);
    }

    /**
     * set source
     *
     * @param string $source
     *
     * @return static
     */
    public function setSource(string $source): static
    {
        return $this->state(fn(array $attributes) => [
            'source' => $source
        ]);
    }
}

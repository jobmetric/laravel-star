<?php

namespace JobMetric\Star;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use JobMetric\Star\Events\StarForgetEvent;
use JobMetric\Star\Events\StarStoredEvent;
use JobMetric\Star\Events\StarUpdateEvent;
use JobMetric\Star\Exceptions\MaxStarException;
use JobMetric\Star\Exceptions\MinStarException;
use JobMetric\Star\Models\Star;
use Throwable;

/**
 * @method morphOne(string $class, string $string)
 * @method morphMany(string $class, string $string)
 */
trait HasStar
{
    /**
     * star has one relationship
     *
     * @return MorphOne
     */
    public function starTo(): MorphOne
    {
        return $this->morphOne(Star::class, 'starable');
    }

    /**
     * star has many relationships
     *
     * @return MorphMany
     */
    public function starsTo(): MorphMany
    {
        return $this->morphMany(Star::class, 'starable');
    }

    /**
     * store star
     *
     * @param int $user_id
     * @param int $star
     *
     * @return array
     * @throws Throwable
     */
    public function starIt(int $user_id, int $star = 3): array
    {
        $minStar = config('star.min_star');
        $maxStar = config('star.max_star');

        if ($star < $minStar) {
            throw new MinStarException($star);
        }

        if ($star > $maxStar) {
            throw new MaxStarException($star);
        }

        /* @var Star $userStar */
        $userStar = $this->starTo()->where('user_id', $user_id)->first();

        if ($userStar) {
            $typeData = 'update';

            $this->starTo()->where('user_id', $user_id)->update(['star' => $star]);

            event(new StarUpdateEvent($userStar, $star));
        } else {
            $typeData = 'store';

            $star = $this->starTo()->create([
                'user_id' => $user_id,
                'star' => $star
            ]);

            event(new StarStoredEvent($star));
        }

        return [
            'type' => $typeData,
            'star_count' => $this->starCount(),
            'star_avg' => $this->starAvg()
        ];
    }

    /**
     * star count
     *
     * @return int
     * @throws Throwable
     */
    public function starCount(): int
    {
        return $this->starTo()->count();
    }

    /**
     * star average
     *
     * @return float
     * @throws Throwable
     */
    public function starAvg(): float
    {
        return (float) $this->starTo()->avg('star');
    }

    /**
     * load star count after a model loaded
     *
     * @return static
     */
    public function withStarCount(): static
    {
        $this->loadCount(['starTo as star_count']);

        return $this;
    }

    /**
     * load star avg after a model loaded
     *
     * @return static
     */
    public function withStarAvg(): static
    {
        $this->loadAvg(['starTo as star_avg'], 'star');

        return $this;
    }

    /**
     * load star or disStar after model loaded
     *
     * @return static
     */
    public function withStar(): static
    {
        $this->load('starTo');

        return $this;
    }

    /**
     * load stars after models loaded
     *
     * @return static
     */
    public function withStars(): static
    {
        $this->load('starsTo');

        return $this;
    }

    /**
     * is stared by user
     *
     * @param int $user_id
     *
     * @return int|null
     */
    public function isStaredStatusBy(int $user_id): ?int
    {
        /* @var Star $star */
        $star = $this->starTo()->where('user_id', $user_id)->first();

        return $star?->star ?? null;
    }

    /**
     * forget star
     *
     * @param int $user_id
     *
     * @return static
     */
    public function forgetStar(int $user_id): static
    {
        /* @var Star $star */
        $star = $this->starTo()->where('user_id', $user_id)->first();

        if ($star) {
            $star->delete();

            event(new StarForgetEvent($star));
        }

        return $this;
    }

    /**
     * forget stars
     *
     * @return static
     */
    public function forgetStars(): static
    {
        /* @var Star $star */
        $this->starsTo()->get()->each(function ($star) {
            $star->delete();

            event(new StarForgetEvent($star));
        });

        return $this;
    }
}

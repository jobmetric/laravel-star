<?php

namespace JobMetric\Star;

use DB;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use JobMetric\Star\Events\StarAddEvent;
use JobMetric\Star\Exceptions\MaxStarException;
use JobMetric\Star\Exceptions\MinStarException;
use JobMetric\Star\Models\Star;
use Throwable;

trait HasStar
{
    /**
     * Get all star ratings for this model.
     *
     * @return MorphMany
     */
    public function stars(): MorphMany
    {
        return $this->morphMany(Star::class, 'starable');
    }

    /**
     * Add or update a star rating.
     *
     * @param int $rate
     * @param Model|null $starredBy
     * @param array $options
     *
     * @return Star
     * @throws Throwable
     */
    public function addStar(int $rate, ?Model $starredBy = null, array $options = []): Star
    {
        $min = config('star.min_star', 1);
        $max = config('star.max_star', 5);

        if ($rate < $min) {
            throw new MinStarException($rate);
        }

        if ($rate > $max) {
            throw new MaxStarException($rate);
        }

        $data = [
            'rate' => $rate,
            'ip' => $options['ip'] ?? request()->ip(),
            'device_id' => $options['device_id'] ?? null,
            'source' => $options['source'] ?? null,
        ];

        if ($starredBy instanceof Model) {
            $data['starred_by_type'] = $starredBy::class;
            $data['starred_by_id'] = $starredBy->getKey();
        }

        $query = $this->stars()->where(function ($q) use ($data) {
            if (isset($data['starred_by_type'], $data['starred_by_id'])) {
                $q->where('starred_by_type', $data['starred_by_type'])
                    ->where('starred_by_id', $data['starred_by_id']);
            } elseif (!empty($data['device_id'])) {
                $q->where('device_id', $data['device_id']);
            }
        });

        /** @var Star|null $existing */
        $existing = $query->first();

        if ($existing) {
            if ($existing->rate === $rate) {
                return $existing;
            }

            $existing->update($data);

            return $existing;
        }

        $star = $this->stars()->create($data);

        event(new StarAddEvent($star));

        return $star;
    }


    /**
     * Remove a star.
     *
     * @param Model|null $starredBy
     * @param string|null $device_id
     *
     * @return bool
     */
    public function removeStar(?Model $starredBy = null, ?string $device_id = null): bool
    {
        /**
         * @var Star|null $star
         */
        $star = $this->findStar($starredBy, $device_id)->first();

        if (!$star) return false;

        $deleted = $star->delete();

        return $deleted > 0;
    }

    /**
     * Check if this model has been starred.
     *
     * @param Model|null $starredBy
     * @param string|null $device_id
     *
     * @return bool
     */
    public function hasStar(?Model $starredBy = null, ?string $device_id = null): bool
    {
        return $this->findStar($starredBy, $device_id)->exists();
    }

    /**
     * Count of all stars.
     *
     * @return int
     */
    public function starCount(): int
    {
        return $this->stars()->count();
    }

    /**
     * Average of all star ratings.
     *
     * @return float
     */
    public function starAvg(): float
    {
        return (float) $this->stars()->avg('rate');
    }

    /**
     * Grouped summary of ratings.
     *
     * @return Collection
     */
    public function starSummary(): Collection
    {
        return $this->stars()
            ->select('rate', DB::raw('count(*) as total'))
            ->groupBy('rate')
            ->pluck('total', 'rate');
    }

    /**
     * Get latest stars.
     *
     * @param int $limit
     * @return EloquentCollection
     */
    public function latestStars(int $limit = 5): EloquentCollection
    {
        return $this->stars()->latest()->take($limit)->get();
    }

    /**
     * Remove all stars (by actor or device).
     *
     * @param Model|null $starredBy
     * @param string|null $device_id
     * @return int
     */
    public function forgetStars(?Model $starredBy = null, ?string $device_id = null): int
    {
        $query = $this->findStar($starredBy, $device_id);

        $stars = $query->get();

        foreach ($stars as $star) {
            $star->delete();
        }

        return $stars->count();
    }

    /**
     * Get internal star query for given actor/device.
     *
     * @param Model|null $starredBy
     * @param string|null $device_id
     *
     * @return MorphMany
     */
    private function findStar(?Model $starredBy = null, ?string $device_id = null): MorphMany
    {
        $query = $this->stars();

        $query->where(function ($q) use ($starredBy, $device_id) {
            if ($starredBy instanceof Model) {
                $q->where([
                    'starred_by_type' => $starredBy::class,
                    'starred_by_id' => $starredBy->getKey(),
                ]);
            }

            if ($device_id) {
                $q->orWhere('device_id', $device_id);
            }
        });

        return $query;
    }

    /**
     * Check if this model has a specific star rating.
     *
     * @param int $rate
     * @param Model|null $starredBy
     * @param string|null $device_id
     *
     * @return bool
     */
    public function isRatedAs(int $rate, ?Model $starredBy = null, ?string $device_id = null): bool
    {
        return $this->findStar($starredBy, $device_id)
            ->where('rate', $rate)
            ->exists();
    }

    /**
     * Get the star value given by actor or device.
     *
     * @param Model|null $starredBy
     * @param string|null $device_id
     *
     * @return int|null
     */
    public function getRatedValue(?Model $starredBy = null, ?string $device_id = null): ?int
    {
        return $this->findStar($starredBy, $device_id)->value('rate');
    }


    /**
     * Check if the rating is above a certain value.
     *
     * @param int $value
     * @param Model|null $starredBy
     * @param string|null $device_id
     *
     * @return bool
     */
    public function isRatedAbove(int $value, ?Model $starredBy = null, ?string $device_id = null): bool
    {
        return $this->findStar($starredBy, $device_id)
            ->where('rate', '>', $value)
            ->exists();
    }

    /**
     * Check if the rating is below a certain value.
     *
     * @param int $value
     * @param Model|null $starredBy
     * @param string|null $device_id
     *
     * @return bool
     */
    public function isRatedBelow(int $value, ?Model $starredBy = null, ?string $device_id = null): bool
    {
        return $this->findStar($starredBy, $device_id)
            ->where('rate', '<', $value)
            ->exists();
    }

}

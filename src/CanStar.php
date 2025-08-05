<?php

namespace JobMetric\Star;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use JobMetric\Star\Models\Star;

/**
 * Trait CanStar
 *
 * Enables a model (e.g. User) to give stars to other models that implement HasStar.
 *
 * @mixin Model
 */
trait CanStar
{
    /**
     * Define a polymorphic one-to-many relationship to Star model.
     *
     * @return MorphMany
     */
    public function starsGiven(): MorphMany
    {
        return $this->morphMany(Star::class, 'starred_by');
    }

    /**
     * Check if this model has starred a specific starable model.
     *
     * @param Model $starable
     *
     * @return bool
     */
    public function hasStarred(Model $starable): bool
    {
        return $this->starsGiven()
            ->where('starable_type', get_class($starable))
            ->where('starable_id', $starable->getKey())
            ->exists();
    }

    /**
     * Get the rating given to a specific model.
     *
     * @param Model $starable
     *
     * @return int|null
     */
    public function starredRate(Model $starable): ?int
    {
        return $this->starsGiven()
            ->where('starable_type', get_class($starable))
            ->where('starable_id', $starable->getKey())
            ->value('rate');
    }

    /**
     * Remove the star rating from a specific model.
     *
     * @param Model $starable
     *
     * @return bool
     */
    public function removeStarFrom(Model $starable): bool
    {
        return $this->starsGiven()
                ->where('starable_type', get_class($starable))
                ->where('starable_id', $starable->getKey())
                ->delete() > 0;
    }

    /**
     * Count how many times this model gave a specific rating value.
     *
     * @param int $rate
     *
     * @return int
     */
    public function countStarGiven(int $rate): int
    {
        return $this->starsGiven()
            ->where('rate', $rate)
            ->count();
    }

    /**
     * Count the total number of stars this model has given.
     *
     * @return int
     */
    public function totalStarsGiven(): int
    {
        return $this->starsGiven()->count();
    }

    /**
     * Get summary of stars this model has given (rate => count).
     *
     * @return Collection<int, int>
     */
    public function starSummary(): Collection
    {
        return $this->starsGiven()
            ->select('rate', DB::raw('count(*) as total'))
            ->groupBy('rate')
            ->pluck('total', 'rate');
    }

    /**
     * Get all models this model has starred.
     *
     * @param string|null $starableClass
     *
     * @return Collection<Model>
     */
    public function starredItems(?string $starableClass = null): Collection
    {
        $query = $this->starsGiven();

        if ($starableClass) {
            $query->where('starable_type', $starableClass);
        }

        return $query->get()->map(fn(Star $s) => $s->starable);
    }

    /**
     * Get all stars given to a specific model type.
     *
     * @param string $starableClass
     *
     * @return Collection<Star>
     */
    public function starsToType(string $starableClass): Collection
    {
        return $this->starsGiven()
            ->where('starable_type', $starableClass)
            ->get();
    }

    /**
     * Get the latest stars given by this model.
     *
     * @param int $limit
     *
     * @return Collection<Star>
     */
    public function latestStarsGiven(int $limit = 5): Collection
    {
        return $this->starsGiven()->latest()->take($limit)->get();
    }
}

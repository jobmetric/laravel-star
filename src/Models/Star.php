<?php

namespace JobMetric\Star\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Request;
use JobMetric\Star\Events\StarRemovedEvent;
use JobMetric\Star\Events\StarRemovingEvent;
use JobMetric\Star\Events\StarUpdatedEvent;
use JobMetric\Star\Events\StarUpdatingEvent;
use JobMetric\Star\Exceptions\InvalidStarActorException;

/**
 * Class Star
 *
 * Represents a star rating (e.g. 1 to 5) given from any actor model (e.g. User, Device)
 * to any target model (e.g. Product, Article).
 *
 * The actor can be identified via a polymorphic relationship (`starred_by`)
 * or anonymously using a `device_id`. IP address and source (e.g. 'web', 'mobile') are
 * recorded optionally for logging, audit, or analytics.
 *
 * @package JobMetric\Star
 *
 * @property int $id
 * @property string|null $starred_by_type The class name of the actor who gave the rating.
 * @property int|null $starred_by_id The ID of the actor who gave the rating.
 * @property string $starable_type The class name of the model that received the rating.
 * @property int $starable_id The ID of the model that received the rating.
 * @property int $rate The rating value (e.g. 1–5, 0–10).
 * @property string|null $ip IP address of the actor (optional).
 * @property string|null $device_id Device ID of the actor (optional).
 * @property string|null $source Source of the rating (e.g. web, mobile).
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Model $starable The model that received the rating.
 * @property-read Model|null $starredBy The model that gave the rating.
 *
 * @method static Builder|Star ofStarable(Model $starable)
 * @method static Builder|Star ofStarredBy(Model $starredBy)
 */
class Star extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'starred_by_type',
        'starred_by_id',
        'starable_type',
        'starable_id',
        'rate',
        'ip',
        'device_id',
        'source',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'starred_by_type' => 'string',
        'starred_by_id' => 'integer',
        'starable_type' => 'string',
        'starable_id' => 'integer',
        'rate' => 'integer',
        'ip' => 'string',
        'device_id' => 'string',
        'source' => 'string',
    ];

    /**
     * Override the table name using config.
     *
     * @return string
     */
    public function getTable(): string
    {
        return config('star.tables.star', parent::getTable());
    }

    /**
     * Boot the model and set up event listeners.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function (Star $star) {
            if (blank($star->ip)) {
                $star->ip = Request::ip();
            }

            if (blank($star->device_id)) {
                $star->device_id = Request::header(config('star.headers.device_id'));
            }

            if (blank($star->source)) {
                $star->source = Request::header(config('star.headers.source'), config('star.default_source'));
            }

            $hasActor = filled($star->starred_by_type) && filled($star->starred_by_id);
            $hasDevice = filled($star->device_id);

            if (!$hasActor && !$hasDevice) {
                throw new InvalidStarActorException;
            }
        });

        static::updating(function (Star $star) {
            event(new StarUpdatingEvent($star, $star->rate));
        });

        static::updated(function (Star $star) {
            event(new StarUpdatedEvent($star, $star->rate));
        });

        static::deleting(function (Star $star) {
            event(new StarRemovingEvent($star));
        });

        static::deleted(function (Star $star) {
            event(new StarRemovedEvent($star));
        });
    }

    /**
     * Get the actor model that gave the star.
     *
     * @return MorphTo
     */
    public function starredBy(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the target model that received the star.
     *
     * @return MorphTo
     */
    public function starable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include stars by a specific actor.
     *
     * @param Builder $query
     * @param Model $starredBy
     *
     * @return Builder
     */
    public function scopeOfStarredBy(Builder $query, Model $starredBy): Builder
    {
        return $query->where([
            'starred_by_type' => get_class($starredBy),
            'starred_by_id' => $starredBy->getKey(),
        ]);
    }

    /**
     * Scope a query to only include stars for a specific target.
     *
     * @param Builder $query
     * @param Model $starable
     *
     * @return Builder
     */
    public function scopeOfStarable(Builder $query, Model $starable): Builder
    {
        return $query->where([
            'starable_type' => get_class($starable),
            'starable_id' => $starable->getKey(),
        ]);
    }

    /**
     * Set the IP address of the star.
     *
     * @param string|null $value
     *
     * @return void
     */
    public function setIpAttribute(?string $value): void
    {
        $this->attributes['ip'] = $value ?? Request::ip();
    }

    /**
     * Set the device ID of the star.
     *
     * @param string|null $value
     *
     * @return void
     */
    public function setDeviceIdAttribute(?string $value): void
    {
        $this->attributes['device_id'] = $value ?? Request::header(config('star.headers.device_id'));
    }

    /**
     * Set the source of the star (e.g. web, app, api).
     *
     * @param string|null $value
     *
     * @return void
     */
    public function setSourceAttribute(?string $value): void
    {
        $this->attributes['source'] = $value ?? Request::header(config('star.headers.source'), config('star.default_source'));
    }

    /**
     * Determine if the current rating is exactly equal to the given value.
     *
     * This is helpful when you need to check for a specific rating match,
     * such as detecting perfect scores or neutral ratings.
     *
     * @param int $value The value to check equality against.
     *
     * @return bool
     */
    public function isRatedAs(int $value): bool
    {
        return $this->rate === $value;
    }

    /**
     * Determine if the current rating is greater than the given value.
     *
     * Useful for comparing the user's rating with a custom threshold,
     * such as detecting ratings above average or triggering badges/rewards.
     *
     * @param int $value The value to compare against.
     *
     * @return bool
     */
    public function isRatedAbove(int $value): bool
    {
        return $this->rate > $value;
    }

    /**
     * Determine if the current rating is less than the given value.
     *
     * This is useful for detecting low ratings to trigger alerts,
     * handle feedback requests, or log dissatisfaction.
     *
     * @param int $value The value to compare against.
     *
     * @return bool
     */
    public function isRatedBelow(int $value): bool
    {
        return $this->rate < $value;
    }
}

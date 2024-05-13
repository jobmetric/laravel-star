<?php

namespace JobMetric\Star\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property mixed user_id
 * @property mixed starable_id
 * @property mixed starable_type
 * @property mixed star
 * @property mixed user
 * @property mixed starable
 */
class Star extends Pivot
{
    protected $fillable = [
        'user_id',
        'starable_id',
        'starable_type',
        'star'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'star' => 'integer'
    ];

    public function getTable()
    {
        return config('star.tables.star', parent::getTable());
    }

    /**
     * user relationship
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * starable relationship
     *
     * @return MorphTo
     */
    public function starable(): MorphTo
    {
        return $this->morphTo();
    }
}

<?php

namespace JobMetric\Star\Events;

use JobMetric\EventSystem\Contracts\DomainEvent;
use JobMetric\EventSystem\Support\DomainEventDefinition;
use JobMetric\Star\Models\Star;

readonly class StarUpdatedEvent implements DomainEvent
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public Star $star,
        public int $rate
    ) {
    }

    /**
     * Returns the stable technical key for the domain event.
     *
     * @return string
     */
    public static function key(): string
    {
        return 'star.updated';
    }

    /**
     * Returns the full metadata definition for this domain event.
     *
     * @return DomainEventDefinition
     */
    public static function definition(): DomainEventDefinition
    {
        return new DomainEventDefinition(self::key(), 'star::base.entity_names.star', 'star::base.events.star_updated.title', 'star::base.events.star_updated.description', 'fas fa-star-half-alt', [
            'star',
            'rating',
            'evaluation',
        ]);
    }
}

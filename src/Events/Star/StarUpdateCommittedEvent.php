<?php

namespace JobMetric\Star\Events\Star;

use JobMetric\EventSystem\Contracts\DomainEvent;
use JobMetric\EventSystem\Support\DomainEventDefinition;

/**
 * Describe a committed Star updated outcome using allowlisted identifiers.
 */
readonly class StarUpdateCommittedEvent implements DomainEvent
{
    /**
     * Capture identifiers without models, input values or credentials.
     *
     * @param string $ownerType
     * @param int $ownerId
     * @param int $userId
     *
     * @return void
     */
    public function __construct(
        public string $ownerType,
        public int $ownerId,
        public int $userId
    ) {
    }

    /**
     * Return the stable key used by automation consumers.
     *
     * @return string
     */
    public static function key(): string
    {
        return 'star.star.updated.committed';
    }

    /**
     * Describe the outcome for translated discovery interfaces.
     *
     * @return DomainEventDefinition
     */
    public static function definition(): DomainEventDefinition
    {
        return new DomainEventDefinition(self::key(), 'star::automation.star_updated.group', 'star::automation.star_updated.title', 'star::automation.star_updated.description', 'fas fa-bolt', ['automation', 'committed', 'star']);
    }
}

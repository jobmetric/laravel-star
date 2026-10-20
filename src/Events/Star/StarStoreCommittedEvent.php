<?php

namespace JobMetric\Star\Events\Star;

use JobMetric\EventSystem\Contracts\DomainEvent;
use JobMetric\EventSystem\Support\DomainEventDefinition;

/**
 * Describe a committed Star created outcome using allowlisted identifiers.
 */
readonly class StarStoreCommittedEvent implements DomainEvent
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
        return 'star.star.stored.committed';
    }

    /**
     * Describe the outcome for translated discovery interfaces.
     *
     * @return DomainEventDefinition
     */
    public static function definition(): DomainEventDefinition
    {
        return new DomainEventDefinition(self::key(), 'star::automation.star_stored.group', 'star::automation.star_stored.title', 'star::automation.star_stored.description', 'fas fa-bolt', ['automation', 'committed', 'star']);
    }
}

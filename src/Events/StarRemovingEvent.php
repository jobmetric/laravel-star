<?php

namespace JobMetric\Star\Events;

use JobMetric\Star\Models\Star;

class StarRemovingEvent
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public Star $star
    )
    {
    }
}

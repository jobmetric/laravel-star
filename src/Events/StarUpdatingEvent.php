<?php

namespace JobMetric\Star\Events;

use JobMetric\Star\Models\Star;

class StarUpdatingEvent
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public Star $star,
        public int  $rate
    )
    {
    }
}

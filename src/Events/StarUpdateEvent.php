<?php

namespace JobMetric\Star\Events;

use JobMetric\Star\Models\Star;

class StarUpdateEvent
{
    public Star $model;
    public int $star;

    /**
     * Create a new event instance.
     */
    public function __construct(Star $model, int $star)
    {
        $this->model = $model;
        $this->star = $star;
    }
}

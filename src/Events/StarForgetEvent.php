<?php

namespace JobMetric\Star\Events;

use JobMetric\Star\Models\Star;

class StarForgetEvent
{
    public Star $model;

    /**
     * Create a new event instance.
     */
    public function __construct(Star $model)
    {
        $this->model = $model;
    }
}

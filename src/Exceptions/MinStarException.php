<?php

namespace JobMetric\Star\Exceptions;

use Exception;
use Throwable;

class MinStarException extends Exception
{
    public function __construct(int $star, int $code = 400, ?Throwable $previous = null)
    {
        $minStar = config('star.min_star');

        parent::__construct("Star must be greater than or equal to $minStar, $star given", $code, $previous);
    }
}

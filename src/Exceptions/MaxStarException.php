<?php

namespace JobMetric\Star\Exceptions;

use Exception;
use Throwable;

class MaxStarException extends Exception
{
    public function __construct(int $star, int $code = 400, ?Throwable $previous = null)
    {
        $maxStar = config('star.max_star');

        parent::__construct(trans('star::base.exceptions.max_star', [
            'maxStar' => $maxStar,
            'star' => $star,
        ]), $code, $previous);
    }
}

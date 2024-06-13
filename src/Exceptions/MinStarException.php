<?php

namespace JobMetric\Star\Exceptions;

use Exception;
use Throwable;

class MinStarException extends Exception
{
    public function __construct(int $star, int $code = 400, ?Throwable $previous = null)
    {
        $minStar = config('star.min_star');

        parent::__construct(trans('star::base.exceptions.min_star', [
            'minStar' => $minStar,
            'star' => $star,
        ]), $code, $previous);
    }
}

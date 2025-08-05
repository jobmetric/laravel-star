<?php

namespace JobMetric\Star\Exceptions;

use Exception;
use Throwable;

class MaxStarException extends Exception
{
    public function __construct(int $rate, int $code = 400, ?Throwable $previous = null)
    {
        $maxRate = config('star.max_rate');

        parent::__construct(trans('star::base.exceptions.max_rate', [
            'max_rate' => $maxRate,
            'rate' => $rate,
        ]), $code, $previous);
    }
}

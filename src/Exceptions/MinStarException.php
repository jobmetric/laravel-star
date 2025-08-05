<?php

namespace JobMetric\Star\Exceptions;

use Exception;
use Throwable;

class MinStarException extends Exception
{
    public function __construct(int $rate, int $code = 400, ?Throwable $previous = null)
    {
        $minRate = config('star.min_rate');

        parent::__construct(trans('star::base.exceptions.min_rate', [
            'min_rate' => $minRate,
            'rate' => $rate,
        ]), $code, $previous);
    }
}

<?php

namespace JobMetric\Star\Exceptions;

use Exception;
use Throwable;

class InvalidStarActorException extends Exception
{
    public function __construct(int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct(trans('star::base.exceptions.invalid_star_actor'), $code, $previous);
    }
}

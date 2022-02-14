<?php

namespace Oto\SchoolGrade\Exception;

use Exception;
use Throwable;

class StudentException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
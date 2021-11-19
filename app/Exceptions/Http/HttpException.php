<?php

namespace App\Exceptions\Http;

use Exception;
use Throwable;

class HttpException extends Exception
{
    public function __construct($message = "404 Not Found", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
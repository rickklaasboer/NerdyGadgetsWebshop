<?php

namespace App\Exceptions\Http;

use Throwable;

class HttpMethodNotAllowedException extends HttpException
{
    public function __construct($message = "Method not allowed", $code = 405, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
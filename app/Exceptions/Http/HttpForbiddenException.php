<?php

namespace App\Exceptions\Http;

use Throwable;

class HttpForbiddenException extends HttpException
{
    public function __construct($message = "Forbidden", $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
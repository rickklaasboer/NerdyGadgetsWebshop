<?php

namespace App\Exceptions\Http;

use Throwable;

class HttpNotFoundException extends HttpException
{
    public function __construct($message = "Not Found", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
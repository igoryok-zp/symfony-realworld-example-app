<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

class UnauthorizedException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        $exceptionMessage = 'Unauthorized';
        if (!empty($message)) {
            $exceptionMessage .= ': ' . $message;
        }
        parent::__construct($exceptionMessage, $code, $previous);
    }
}

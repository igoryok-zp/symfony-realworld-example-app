<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

class NotFoundException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        $exceptionMessage = 'Not Found';
        if (!empty($message)) {
            $exceptionMessage .= ': ' . $message;
        }
        parent::__construct($exceptionMessage, $code, $previous);
    }
}

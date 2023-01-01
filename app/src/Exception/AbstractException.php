<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

abstract class AbstractException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        $exceptionMessage = $this->getName();
        if (!empty($message)) {
            $exceptionMessage .= ': ' . $message;
        }
        parent::__construct($exceptionMessage, $code, $previous);
    }

    abstract protected function getName(): string;
}

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

    protected function getName(): string
    {
        $classParts = explode('\\', static::class);
        $className = array_pop($classParts);
        $classWords = preg_split('/(?=[A-Z])/', $className);
        $nameParts = is_array($classWords) ? array_slice($classWords, 1, -1) : [];
        return implode(' ', $nameParts);
    }
}

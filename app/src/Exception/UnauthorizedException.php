<?php

declare(strict_types=1);

namespace App\Exception;

class UnauthorizedException extends AbstractException
{
    protected function getName(): string
    {
        return 'Unauthorized';
    }
}

<?php

declare(strict_types=1);

namespace App\Exception;

class ForbiddenException extends AbstractException
{
    protected function getName(): string
    {
        return 'Forbidden';
    }
}

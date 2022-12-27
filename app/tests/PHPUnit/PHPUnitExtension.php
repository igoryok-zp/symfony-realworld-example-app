<?php

declare(strict_types=1);

namespace App\Tests\PHPUnit;

use PHPUnit\Runner\BeforeFirstTestHook;
use Symfony\Component\Process\Process;

class PHPUnitExtension implements BeforeFirstTestHook
{
    private function exec(array $command): void
    {
        $process = new Process(['php', './bin/console', '--env=test', ...$command]);
        $process->mustRun();
    }

    public function executeBeforeFirstTest(): void
    {
        $this->exec(['doctrine:database:create', '--if-not-exists']);
        $this->exec(['doctrine:schema:drop', '--full-database', '--force']);
        $this->exec(['doctrine:migrations:migrate', '--no-interaction']);
        $this->exec(['doctrine:schema:update', '--force']);
        $this->exec(['hautelook:fixtures:load', '--no-interaction']);
    }
}

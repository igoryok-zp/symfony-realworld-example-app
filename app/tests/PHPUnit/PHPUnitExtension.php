<?php

declare(strict_types=1);

namespace App\Tests\PHPUnit;

use Symfony\Component\Process\Process;
use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use PHPUnit\Event\TestRunner\ExecutionStarted;
use PHPUnit\Event\TestRunner\ExecutionStartedSubscriber;

class PHPUnitExtension implements Extension, ExecutionStartedSubscriber
{
    private function exec(string ...$command): void
    {
        $process = new Process(['php', './bin/console', '--env=test', ...$command]);
        $process->mustRun();
    }

    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        $facade->registerSubscriber($this);
    }

    public function notify(ExecutionStarted $event): void
    {
        $this->exec('doctrine:database:create', '--if-not-exists');
        $this->exec('doctrine:schema:drop', '--full-database', '--force');
        $this->exec('doctrine:migrations:migrate', '--no-interaction');
        $this->exec('doctrine:schema:update', '--force');
        $this->exec('hautelook:fixtures:load', '--no-interaction');
    }
}

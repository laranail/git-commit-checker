<?php declare(strict_types=1);

namespace Simtabi\Laranail\GitCommitChecker\Providers;

use Simtabi\Laranail\GitCommitChecker\Commands\InstallCommand;
use Simtabi\Laranail\GitCommitChecker\Commands\PreCommitHookCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            InstallCommand::class,
            PreCommitHookCommand::class,
        ]);
    }
}

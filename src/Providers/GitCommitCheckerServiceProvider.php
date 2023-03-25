<?php declare(strict_types=1);

namespace Simtabi\Laranail\GitCommitChecker\Providers;

use Illuminate\Support\ServiceProvider;

class GitCommitCheckerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'git-commit-checker');
    }
    public function boot()
    {
        $this->app->register(CommandServiceProvider::class);

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'git-commit-checker');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/config.php' => config_path('git-commit-checker.php'),
            ], 'laranail::git-commit-checker-config');

            $this->publishes([
                __DIR__ . '/../../resources/views'   => resource_path('views/vendor/git-commit-checker')
            ], 'laranail::git-commit-checker-views');
        }
    }
}

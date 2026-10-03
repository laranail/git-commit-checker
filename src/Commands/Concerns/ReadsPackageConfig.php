<?php

declare(strict_types=1);

namespace Simtabi\Laranail\GitCommitChecker\Commands\Concerns;

/**
 * One place that knows where this package's configuration lives.
 *
 * The provider registers it at `laranail.git-commit-checker`. Until 2026-10 the commands read the
 * bare `git-commit-checker` key instead, so an unpublished install saw null everywhere: `install`
 * iterated over nothing and the hook reported itself disabled. A config published before then landed
 * at the bare `config/git-commit-checker.php` and is still honoured (deprecated), so an application
 * that already overrode it keeps its override.
 */
trait ReadsPackageConfig
{
    protected function packageConfig(string $key, mixed $default = null): mixed
    {
        $config = $this->laravel['config'];

        $root = $config->has('git-commit-checker.hooks') ? 'git-commit-checker' : 'laranail.git-commit-checker';

        return $config->get("{$root}.{$key}", $default);
    }
}

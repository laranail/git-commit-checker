<?php

declare(strict_types=1);

namespace Simtabi\Laranail\GitCommitChecker\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Simtabi\Laranail\GitCommitChecker\Providers\GitCommitCheckerServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [GitCommitCheckerServiceProvider::class];
    }
}

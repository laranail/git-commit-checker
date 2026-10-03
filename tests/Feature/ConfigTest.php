<?php

declare(strict_types=1);

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Simtabi\Laranail\GitCommitChecker\Commands\PreCommitHookCommand;

/**
 * The shipped config named `Simtabi\GitCommitChecker\Commands\PreCommitHookCommand`, a namespace this
 * package never had, so `install` aborted with "Class [...] not found" on every hook.
 */
it('names hook commands that exist', function (): void {
    $hooks = Config::get('laranail.git-commit-checker.hooks');

    expect($hooks)->toBeArray()->not->toBeEmpty();

    foreach ($hooks as $hook => $class) {
        expect(class_exists($class))->toBeTrue("hook [$hook] names a class that does not exist: $class")
            ->and(is_subclass_of($class, Command::class))->toBeTrue();
    }

    expect($hooks['pre-commit'])->toBe(PreCommitHookCommand::class);
});

/**
 * Registered at `laranail.git-commit-checker`, read at the bare `git-commit-checker` key: unpublished,
 * `enabled` was null, so the hook reported "disabled" and checked nothing, ever.
 */
it('runs the hook when enabled at the registered key', function (): void {
    Config::set('laranail.git-commit-checker.enabled', true);

    $this->artisan('git-commit-checker:pre-commit-hook')
        ->doesntExpectOutputToContain('is disabled');
});

it('skips the hook when disabled at the registered key', function (): void {
    Config::set('laranail.git-commit-checker.enabled', false);

    $this->artisan('git-commit-checker:pre-commit-hook')
        ->expectsOutputToContain('is disabled')
        ->assertSuccessful();
});

/** An application that published the old bare config/git-commit-checker.php keeps its override. */
it('still honours a config published to the old bare path', function (): void {
    Config::set('git-commit-checker', array_replace(Config::get('laranail.git-commit-checker'), ['enabled' => false]));
    Config::set('laranail.git-commit-checker.enabled', true);

    $this->artisan('git-commit-checker:pre-commit-hook')
        ->expectsOutputToContain('is disabled');
});

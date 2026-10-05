<?php

declare(strict_types=1);

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Console\Kernel;
use Simtabi\Laranail\GitCommitChecker\Commands\InstallCommand;
use Simtabi\Laranail\GitCommitChecker\Commands\PreCommitHookCommand;

/**
 * Every name this package registers into a framework-owned registry.
 *
 * These are flat maps keyed by the name, so a second package claiming one does not collide loudly --
 * it **silently replaces** the first, and the damage surfaces elsewhere as a missing view or the
 * wrong config. `git-commit-checker` is a plausible collision with a sibling package or the consuming
 * application's own.
 *
 * This package shipped claiming the bare name on both. Its publish tags were already vendor-scoped,
 * which is what makes the gap easy to miss by eye: two of the four names were right.
 */
it('registers its config under vendor and slug, never a bare one', function (): void {
    expect(Config::get('laranail.git-commit-checker'))->toBeArray()
        ->and(Config::get('git-commit-checker'))->toBeNull();
});

it('registers its views under vendor and slug, never a bare one', function (): void {
    $hints = View::getFinder()->getHints();

    expect($hints)->toHaveKey('laranail/git-commit-checker')
        ->and($hints)->not->toHaveKey('git-commit-checker');
});

/**
 * Artisan's command map is flat too. `git-commit-checker:install` is the package's own slug, but
 * the family shape is `laranail::<slug>.<command>`, so the source of a command is unambiguous and no
 * sibling can claim the name. The old names stay as aliases of the same command, so scripts and
 * already-installed hooks keep working, and say they are deprecated when used.
 */
dataset('commands', [
    'install'         => ['laranail::git-commit-checker.install', 'git-commit-checker:install', InstallCommand::class],
    'pre-commit hook' => ['laranail::git-commit-checker.pre-commit-hook', 'git-commit-checker:pre-commit-hook', PreCommitHookCommand::class],
]);

it('registers its commands under vendor and slug', function (string $scoped, string $bare, string $class): void {
    $commands = Artisan::all();

    expect($commands)->toHaveKey($scoped)
        ->and($commands[$scoped])->toBeInstanceOf($class)
        ->and($commands[$scoped]->getName())->toBe($scoped);
})->with('commands');

it('keeps the bare name only as a deprecated alias of the scoped command', function (string $scoped, string $bare): void {
    $command = Artisan::all()[$scoped];

    // Exactly the one legacy name: an extra bare alias would hand back the collision the scoped
    // name exists to prevent.
    expect($command->getAliases())->toBe([$bare])
        ->and($this->app[Kernel::class]->all()[$bare]->getName())->toBe($scoped);
})->with('commands');

it('warns when a command is invoked by its deprecated name', function (): void {
    Config::set('laranail.git-commit-checker.enabled', false);

    // Artisan::output() rather than expectsOutputToContain(): the mocked output matches each
    // expectation against one write, and both halves of the warning are a single line.
    expect(Artisan::call('git-commit-checker:pre-commit-hook'))->toBe(0)
        ->and(Artisan::output())
        ->toContain('[git-commit-checker:pre-commit-hook] is a deprecated alias')
        ->toContain('Use [laranail::git-commit-checker.pre-commit-hook] instead');
});

it('does not warn when a command is invoked by its scoped name', function (): void {
    Config::set('laranail.git-commit-checker.enabled', false);

    $this->artisan('laranail::git-commit-checker.pre-commit-hook')
        ->doesntExpectOutputToContain('deprecated')
        ->assertSuccessful();
});

it('writes hooks that call the scoped command name', function (): void {
    // Hooks installed before the rename call the bare name and keep working through the alias;
    // hooks installed from now on must not depend on it.
    $install = Artisan::all()['laranail::git-commit-checker.install'];
    $script = (new ReflectionMethod($install, 'generateHookScript'))
        ->invoke($install, (new PreCommitHookCommand)->getName());

    expect($script)->toContain('laranail::git-commit-checker.pre-commit-hook')
        ->not->toContain(' git-commit-checker:pre-commit-hook');
});

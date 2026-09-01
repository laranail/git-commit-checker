<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

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

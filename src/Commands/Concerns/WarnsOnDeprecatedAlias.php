<?php

declare(strict_types=1);

namespace Simtabi\Laranail\GitCommitChecker\Commands\Concerns;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Prints a one-line deprecation warning when a command is invoked by one of
 * its bare legacy aliases (`git-commit-checker:*`) rather than by its
 * vendor-scoped name (`laranail::git-commit-checker.*`).
 *
 * A local copy of `laranail/package-scaffolder`'s trait of the same name:
 * `laranail/console` main has no deprecated-alias support yet. When it gains
 * one, this delegates to it.
 *
 * The aliases stay registered, so every existing script keeps working; the
 * warning names the replacement. Detection reads the name the caller actually
 * typed: the input's first argument is the command token for `php artisan`,
 * `Artisan::call()` and `$this->call()` alike, while `getName()` is always the
 * canonical name.
 *
 * Symfony calls initialize() after binding the input and before interact(),
 * so the warning prints before any prompt.
 */
trait WarnsOnDeprecatedAlias
{
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        $invokedAs = $input->getFirstArgument();

        if (! is_string($invokedAs) || $invokedAs === $this->getName() || ! in_array($invokedAs, $this->getAliases(), true)) {
            return;
        }

        $output->writeln(sprintf(
            '<comment>Deprecated:</comment> [%s] is a deprecated alias and will be removed in the next minor after 0.1. Use [%s] instead.',
            $invokedAs,
            (string) $this->getName(),
        ));
    }
}

<?php

declare(strict_types=1);

namespace Simtabi\Laranail\GitCommitChecker\Commands\Concerns;

use ReflectionProperty;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

/**
 * Lets a command use the laranail naming shape `laranail::git-commit-checker.<command>`.
 *
 * Symfony's {@see SymfonyCommand::validateName()} regex (`^[^:]++(:[^:]++)*$`)
 * rejects the empty segment in `::`, so this trait sets the name (and aliases)
 * past that validator by writing the private property directly. Dispatch still
 * works because Symfony resolves an exact command name (and its registered
 * aliases) before its `:`-splitting namespace lookup runs, which is what lets
 * `laranail::git-commit-checker.install` be found at all.
 *
 * The trait also applies an optional `$commandAliases` list during construction
 * (Laravel invokes {@see setName()} while building the command from its
 * signature).
 *
 * **The list is declared here, defaulting to empty**, and that is a fix rather
 * than a style choice: it used to be read off the consuming command without
 * being declared anywhere, so any command that used the trait *without*
 * declaring the property died with `Undefined property: …::$commandAliases` the
 * moment Laravel built it. A trait that requires an undeclared property of its
 * user is a trap, and the failure lands at boot rather than at the call site.
 * A command that wants aliases still declares its own list, which overrides
 * this default.
 *
 * Whatever it declares must itself be vendor-scoped. The only bare names this
 * package carries are the two legacy command names, kept as deprecated aliases
 * through `#[AsCommand]` so existing scripts and installed git hooks still run.
 *
 * Self-contained: this is a local copy of the canonical laranail trait in
 * `laranail/console`, because this package's `require` holds no `laranail/*`
 * entry and taking console on for one trait would make every consumer declare
 * a VCS repository for it. Its conformance test declares that reason so
 * `package-tools/scripts/verify-trait-copies.py` can falsify it.
 *
 * @internal
 */
trait SupportsNamespacedNames
{
    public function setName(string $name): static
    {
        $this->writeCommandName('name', $name);

        $aliases = $this->declaredCommandAliases();

        if ($aliases !== []) {
            $this->setAliases($aliases);
        }

        return $this;
    }

    /**
     * @param iterable<int, string> $aliases
     */
    public function setAliases(iterable $aliases): static
    {
        $this->writeCommandName('aliases', is_array($aliases) ? $aliases : iterator_to_array($aliases));

        return $this;
    }

    /**
     * The consuming command's own `$commandAliases`, if it declares one.
     *
     * Deliberately NOT a property on this trait. A trait cannot declare a property that the using
     * class also declares with a different default -- PHP rejects the composition outright with a
     * fatal -- so declaring it here made the documented usage ("a command that wants aliases
     * declares its own list") impossible to actually write.
     *
     * Declaring it was itself the fix for the opposite bug, where reading an undeclared property
     * threw at boot. Reading it defensively fixes both at once.
     *
     * @return list<string>
     */
    private function declaredCommandAliases(): array
    {
        if (! property_exists($this, 'commandAliases') || ! is_array($this->commandAliases)) {
            return [];
        }

        // Filtered rather than cast: the property is the consuming command's, so its contents are
        // not this trait's to assume. A stray null would reach Symfony's setAliases() as a type
        // error at boot, which is the failure mode this whole method exists to avoid.
        return array_values(array_filter(
            $this->commandAliases,
            static fn (mixed $alias): bool => is_string($alias) && $alias !== '',
        ));
    }

    private function writeCommandName(string $property, mixed $value): void
    {
        // The name/aliases are private on Symfony's base Command; writing them
        // directly bypasses validateName()'s rejection of the `::` separator.
        (new ReflectionProperty(SymfonyCommand::class, $property))->setValue($this, $value);
    }
}

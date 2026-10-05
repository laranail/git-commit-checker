# Architecture

What this package registers, and the names it claims.

## Public names

Laravel keeps view namespaces and config keys in **flat global maps**. A second package claiming a
key does not collide loudly — it silently replaces the first, and the failure surfaces far away as a
missing view or the wrong config value. A bare slug like `git-commit-checker` is a plausible collision with a
sibling package, a third-party one, or the consuming application's own.

| Surface | Name |
|---|---|
| Config key | `laranail.git-commit-checker` |
| View namespace | `laranail/git-commit-checker` |
| Publish tags | `laranail::git-commit-checker-*` |
| Artisan commands | `laranail::git-commit-checker.install`, `laranail::git-commit-checker.pre-commit-hook` |

Views take the slash form because Laravel interpolates the namespace into the override path, so a
published override lands in `resources/views/vendor/laranail/git-commit-checker` — one directory per vendor
rather than thirty siblings flat in the `vendor` root.

**This package previously claimed the bare `git-commit-checker` for both the config key and the view namespace.**
Its publish tags were already vendor-scoped, which is what made the gap easy to miss by eye: two of
the four names were right.

**The two commands were `git-commit-checker:install` and `git-commit-checker:pre-commit-hook`.**
Both names stay registered as aliases of the scoped commands, so scripts and hooks written before the
rename still run, and print a deprecation line naming the replacement when used. They may be removed
in the next minor after 0.1. The hook script `install` writes calls the scoped name.

Symfony's command-name validator rejects the empty segment in `::`, so the commands use a local copy
of `laranail/console`'s `SupportsNamespacedNames` trait. A copy rather than a dependency: this
package requires no `laranail/*` package, and taking console on for one trait would make every
consumer declare a VCS repository for it. `tests/Feature/NamespacedNamesConformanceTest.php` holds
the copy to the canonical behaviour.

`tests/Feature/NamingConventionTest.php` asserts this against the **live registries** —
`View::getFinder()->getHints()`, the config repository and the Artisan command map — rather than by grepping the provider, so
the guard survives a refactor of the registration code.

## Modernisation

This package predates the family conventions. Adopting it moved:

- PHP `>=8.0` → `^8.4.1 || ^8.5`, the family floor.
- `laravel/framework >=9.x` → the individual `illuminate/*` components it actually uses. A package
  should not require the whole framework.
- `laravel/pint` out of `require` and into `require-dev`. It is a dev tool, and requiring it forced
  it into every consuming application.

---

[← Docs index](../README.md#documentation)

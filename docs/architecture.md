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

Views take the slash form because Laravel interpolates the namespace into the override path, so a
published override lands in `resources/views/vendor/laranail/git-commit-checker` — one directory per vendor
rather than thirty siblings flat in the `vendor` root.

**This package previously claimed the bare `git-commit-checker` for both the config key and the view namespace.**
Its publish tags were already vendor-scoped, which is what made the gap easy to miss by eye: two of
the four names were right.

`tests/Feature/NamingConventionTest.php` asserts this against the **live registries** —
`View::getFinder()->getHints()` and the config repository — rather than by grepping the provider, so
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

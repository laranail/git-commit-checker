# Changelog

All notable changes to `laranail/git-commit-checker` are documented in this file.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Fixed

- **`install` works.** The shipped config named the hook command
  `Simtabi\GitCommitChecker\Commands\PreCommitHookCommand`, a namespace this package never had,
  so `install` aborted with *"Class [...] not found"*. It now names
  `Simtabi\Laranail\GitCommitChecker\Commands\PreCommitHookCommand`.
- **The config is read where the provider registers it.** Both commands read the bare
  `git-commit-checker` key while the defaults live at `laranail.git-commit-checker`, so an
  unpublished install iterated no hooks and the hook always reported itself disabled.

### Deprecated

- **A config published to `config/git-commit-checker.php`** is still honoured. Republish with
  `--tag=laranail::git-commit-checker-config`, which now writes
  `config/laranail/git-commit-checker.php`, and delete the old file.
- `git-commit-checker:install` and `git-commit-checker:pre-commit-hook`. Both stay registered as
  aliases of the scoped commands and print a deprecation line naming the replacement when used,
  so hooks installed before this release keep working. Re-run
  `laranail::git-commit-checker.install` to rewrite a hook. The aliases may be removed in the next
  minor after 0.1.

### Added

- A `Quick start` section in the README.

### Changed

- **The commands are `laranail::git-commit-checker.install` and
  `laranail::git-commit-checker.pre-commit-hook`**, the family's `laranail::<slug>.<command>` shape.
  The hook script `install` writes calls the scoped name. A local copy of `laranail/console`'s
  `SupportsNamespacedNames` trait lets Symfony accept the `::`; the package still requires no
  `laranail/*` package.
- The `repositories` block replaces Packagist with a copy that excludes `laranail/*`, so
  `laranail/package-tools` can only resolve from its VCS repository. This is the family's standard
  block.
- The Imani Manyara author entry carries `imani@simtabi.com`.

## v0.1.0

### Changed

- **Public names are vendor-scoped.** The config key is `laranail.git-commit-checker` and the view namespace
  `laranail/git-commit-checker`, where both were the bare `git-commit-checker`. Publish tags were already scoped.
  Breaking for anyone reading the old names.
- **PHP `>=8.0` → `^8.4.1 || ^8.5`**, the family floor.
- **Requires the `illuminate/*` components it uses** rather than `laravel/framework`.
- **`laravel/pint` moved to `require-dev`.** It was in `require`, forcing a dev tool into every
  consuming application.

### Added

- A test suite and CI, neither of which this package had.
- `LICENSE` (MIT), and a `docs/` tree.

[Unreleased]: https://github.com/laranail/git-commit-checker/compare/v0.1.0...HEAD

# Changelog

All notable changes to `laranail/git-commit-checker` are documented in this file.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

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

### Added

- A `Quick start` section in the README.

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

# Changelog

All notable changes to `laranail/git-commit-checker` are documented in this file.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

_Nothing yet._

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

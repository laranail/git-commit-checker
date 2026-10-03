# laranail/git-commit-checker

[![Tests](https://github.com/laranail/git-commit-checker/actions/workflows/tests.yml/badge.svg)](https://github.com/laranail/git-commit-checker/actions/workflows/tests.yml)
[![Static analysis](https://github.com/laranail/git-commit-checker/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/laranail/git-commit-checker/actions/workflows/static-analysis.yml)
[![License MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

`laranail/git-commit-checker` is not published to Packagist, so there is no registry-version badge to show: see [Install](#install).

> Git pre-commit hooks for coding-standard and syntax checks in Laravel projects.

Requires PHP `^8.4.1 || ^8.5` and Laravel `^13.0`.

## Install

```bash
composer require laranail/git-commit-checker
```

## Quick start

```bash
# Write .git/hooks/pre-commit and, optionally, a pint.json preset
php artisan git-commit-checker:install

# Every commit now runs Pint in --test mode over the changed PHP files;
# run the same check by hand without committing
php artisan git-commit-checker:pre-commit-hook
```

How the hook is wired is in [Architecture](docs/architecture.md); everything else is in the [documentation index](#documentation).

## <a name="documentation"></a>Documentation

Full documentation is at
**[opensource.simtabi.com/documentation/laranail/git-commit-checker](https://opensource.simtabi.com/documentation/laranail/git-commit-checker/)**.

### Project

- [Architecture](docs/architecture.md) — what this package registers, and under which names.

## License

MIT. See [LICENSE](LICENSE).

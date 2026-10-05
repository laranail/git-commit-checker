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

## Quick start guide and usage

### Getting started

1. Run it from a Git checkout in a local environment: `laranail::git-commit-checker.install` refuses to
   write a hook when `APP_ENV` is not `local` or there is no `.git` directory.
2. Optionally publish the config to change the hooks or the Pint presets. The pre-commit check is on
   by default; `GIT_COMMIT_CHECKER_ENABLED=false` switches it off.

   ```bash
   php artisan vendor:publish --tag=laranail::git-commit-checker-config
   ```

### Usage

```bash
# Write .git/hooks/pre-commit and, optionally, a pint.json preset
php artisan laranail::git-commit-checker.install

# Every commit now runs Pint in --test mode over the changed PHP files;
# run the same check by hand without committing
php artisan laranail::git-commit-checker.pre-commit-hook
```

> `git-commit-checker:install` and `git-commit-checker:pre-commit-hook` are deprecated aliases of
> these two commands. They still run, print a deprecation line, and may be removed in the next minor
> after 0.1. A hook installed before the rename calls the old name and keeps working; run
> `laranail::git-commit-checker.install` again to rewrite it with the new one.

How the hook is wired is in [Architecture](docs/architecture.md); everything else is in the [documentation index](#documentation).

## <a name="documentation"></a>Documentation

Full documentation is at
**[opensource.simtabi.com/documentation/laranail/git-commit-checker](https://opensource.simtabi.com/documentation/laranail/git-commit-checker/)**.

### Project

- [Architecture](docs/architecture.md) — what this package registers, and under which names.

## License

MIT. See [LICENSE](LICENSE).

![banner](.github/assets/banner.png?raw=true)

Git Commit Checker
============
A highly opinionated Git pre-commit hooks for coding standards checking, and syntax fixing for the Laravel framework.

Requirements
============

To be able to use this package you need to meet the following minimum requirements:
1. Laravel framework >= v9
2. PHP >= v8.0

****

## Installation

You can install the package via composer:

```shell
composer require laranail/git-commit-checker
```

To publish views and config files for further manipulation, run the following commands:

```bash
php artisan vendor:publish laranail::git-commit-checker-config
php artisan vendor:publish laranail::git-commit-checker-views
```

### Install GIT hooks

Run this command to install:

```shell
php artisan git-commit-checker:install
```

Run test manually (made sure you've added all changed files to git stage):

```shell
php artisan git-commit-checker:pre-commit-hook
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email minhsang2603@gmail.com instead of using the issue tracker.

Credits
============
This project wouldn't have been possible without the inspiration and ideas borrowed from many sources across the open source community,
we say thank you to the respective authors.

## Contributors

- [All Contributors](CONTRIBUTORS.md)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

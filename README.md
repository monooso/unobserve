# Unobserve

<p>
  <a href="https://github.com/monooso/unobserve/actions/workflows/lint-and-test.yml"><img src="https://github.com/monooso/unobserve/actions/workflows/lint-and-test.yml/badge.svg" alt="Lint and Test Status"/></a>
  <a href="https://packagist.org/packages/monooso/unobserve"><img src="https://poser.pugx.org/monooso/unobserve/v/stable.svg" alt="Latest Stable Version"/></a>
  <a href="https://packagist.org/packages/monooso/unobserve"><img src="https://poser.pugx.org/monooso/unobserve/license.svg" alt="License"/></a>
</p>

## About Unobserve
When testing Laravel applications, we frequently need to "silence" events, so as not to trigger additional side-effects. [Laravel's `Event::fake` method](https://laravel.com/docs/mocking#event-fake) is useful, but muting a specific [model observer](https://laravel.com/docs/eloquent#observers) is still problematic.

Unobserve takes care of that, making it easy to mute and unmute an observer at will.

## Requirements and installation
Unobserve supports only the latest major version of [Laravel](https://laravel.com/). See [`composer.json`](composer.json) for the exact version requirements.

Install it using [Composer](https://getcomposer.org/):

```bash
composer require monooso/unobserve
```

## Usage
First, add the `CanMute` trait to your observer class.

```php
<?php

namespace App\Observers;

use Monooso\Unobserve\CanMute;

class UserObserver
{
    use CanMute;
}
```

You can now mute and unmute your observer as needed:

```php
UserObserver::mute();
UserObserver::unmute();
```

## Mute options
Mute all observer events:

```php
UserObserver::mute();
```

Mute specific observer events:

```php
UserObserver::mute('creating');
UserObserver::mute(['creating', 'created']);
```

## Local development
Unobserve is very stable at this point. If you have any feature ideas please [open an issue](https://github.com/monooso/unobserve/issues/new) before doing any work.

Development and testing happen entirely inside [Podman](https://podman.io/) containers, driven by the `./dev` script. You do **not** need to install PHP, Composer, or any project dependencies on your machine.

The `dev` helper script builds a disposable PHP + Composer image for the PHP version you name, mounts your working tree into it, and runs your command. Any changes to `composer.json` and `composer.lock` are written back to your disk so they can be committed.

### Prerequisites
[Podman](https://podman.io/) 5.x or later.

### Common tasks
The `dev` script always accepts the target PHP version as the first argument. The first time you run a command with a given PHP version, it builds the image automatically.

| Command | Purpose |
|---|---|
| `./dev 8.3` | Open a shell on PHP 8.3 |
| `./dev 8.3 test` | Run the test suite |
| `./dev 8.3 lint` | Run Laravel Pint |
| `./dev 8.3 composer install` | Install dependencies from the lock file |
| `./dev 8.3 composer update` | Re-resolve dependencies (rewrites `composer.lock`) |
| `./dev 8.3 composer outdated` | List outdated packages |
| `./dev 8.3 php -v` | Run any command in the container |
| `./dev build 8.3` | Rebuild the image for a version |

Run `./dev --help` for the full reference.

### Upgrading PHP or Laravel

1. Create a branch.
2. Edit `composer.json` to widen the relevant constraints, e.g. `"php": "^8.3"` and `"illuminate/support": "^13.0"`.
3. Re-resolve dependencies against the target PHP version:

   ```bash
   ./dev 8.3 composer update
   ```

4. In the event of a conflict, investigate and iterate:

   ```bash
   ./dev 8.3 composer why-not laravel/framework 13.0.0
   ./dev 8.3 composer require illuminate/support:"^13.0" --no-update
   ./dev 8.3 composer update
   ```

5. Run the tests and linter:

   ```bash
   ./dev 8.3 test
   ./dev 8.3 lint
   ```

6. Commit `composer.json` and `composer.lock`.

Each PHP version keeps its own dependency cache, so several versions can be tested side by side (e.g. `./dev 8.2 test`, `./dev 8.3 test`) without interfering with each other.

## License
Unobserve is open source software, released under [the MIT license](https://github.com/monooso/unobserve/blob/main/LICENSE.txt).

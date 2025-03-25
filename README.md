# Unobserve

<p>
  <a href="https://github.com/monooso/unobserve/actions/workflows/lint-and-test.yml"><img src="https://github.com/monooso/unobserve/actions/workflows/lint-and-test.yml/badge.svg" alt="Lint and Test Status"/></a>
  <a href="https://scrutinizer-ci.com/g/monooso/unobserve"><img src="https://img.shields.io/scrutinizer/g/monooso/unobserve.svg" alt="Quality Score"/></a>
  <a href="https://scrutinizer-ci.com/g/monooso/unobserve"><img src="https://img.shields.io/scrutinizer/coverage/g/monooso/unobserve.svg" alt="Coverage"/></a>
  <a href="https://packagist.org/packages/monooso/unobserve"><img src="https://poser.pugx.org/monooso/unobserve/v/stable.svg" alt="Latest Stable Version"/></a>
  <a href="https://packagist.org/packages/monooso/unobserve"><img src="https://poser.pugx.org/monooso/unobserve/license.svg" alt="License"/></a>
</p>

## About Unobserve
When testing Laravel applications, we frequently need to "silence" events, so as not to trigger additional side-effects. [Laravel's `Event::fake` method](https://laravel.com/docs/mocking#event-fake) is useful, but muting a specific [model observer](https://laravel.com/docs/eloquent#observers) is still problematic.

Unobserve takes care of that, making it easy to mute and unmute an observer at will.

## Requirements and installation
Select the appropriate version of Unobserve from the following table.

| Unobserve | Laravel | PHP |
|:-------|:-----------------|:------------|
| 1.x    | `^5.8`, `^6.0`   | `^7.2`      |
| 2.x    | `^7.0`           | `^7.2.5`    |
| 3.x    | `^8.0`           | `^7.3.0`    |
| 4.x    | `^8.0`           | `^8.0.0`    |
| 5.x    | `^9.0`, `^10.0`  | `^8.0.0`    |
| 6.x    | `^11.0`, `12.0`  | `^8.2.0`    |

Install Unobserve using [Composer](https://getcomposer.org/):

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

## Development
Unobserve is very stable at this point. If you have any feature ideas please [open an issue](https://github.com/monooso/unobserve/issues/new) before doing any work.

Here's how to set everything up for local development on Unobserve [^development]:

[^development]: I'm assuming you know your way around PHP, and already have your environment set up.

1. Clone the repository.
2. Install the dependencies by running `composer install`.
3. Install the code quality tools by running `composer install --working-dir=tools/php-cs-fixer`.
4. Set your Git hooks directory to `.githooks` by running `git config core.hooksPath .githooks`.

## License
Unobserve is open source software, released under [the MIT license](https://github.com/monooso/unobserve/blob/master/LICENSE.txt).

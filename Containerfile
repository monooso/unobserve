# Containerfile — a disposable PHP + Composer toolchain for working on this
# package with Podman. Nothing PHP-related is required on the host, and nothing
# from this project is baked into the image.
#
# The image is a *toolchain only*: PHP, Composer, and the PHP extensions
# Laravel needs. Your source and dependencies are bind-mounted in at run time
# (see the ./dev wrapper), so `composer update`, `composer require`, and
# `phpunit` all operate on your live working tree — changes to composer.json /
# composer.lock are written straight back to disk for you to commit.
#
# In practice you should not need to call `podman build` directly. The ./dev
# script builds the right image automatically on first use. Run `./dev --help`.
#
# Build for a specific PHP version (the package targets PHP ^8.0):
#   podman build --build-arg PHP_VERSION=8.3 -t apposite:php8.3 .
#
# Add a code-coverage driver (for `phpunit --coverage-text`, etc.):
#   podman build --build-arg PHP_VERSION=8.3 --build-arg INSTALL_XDEBUG=1 \
#       -t apposite:php8.3-cov .
#   podman run --rm -v "$PWD":/app -v apposite-vendor-php8.3:/app/vendor \
#       apposite:php8.3-cov vendor/bin/phpunit --coverage-text

ARG PHP_VERSION=8.0
FROM php:${PHP_VERSION}-cli

# Tools Composer may need: git is a fallback for packages that ship no dist
# archive; unzip extracts archives. The base image already ships the PHP
# extensions Laravel depends on (mbstring, xml, openssl, pdo, ...).
RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip \
    && rm -rf /var/lib/apt/lists/*

# Composer, copied from the official image (no installer script needed).
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Builds PHP extensions, resolving any system build dependencies, and silently
# skips anything already compiled into the base image.
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions \
    && install-php-extensions bcmath mbstring xml

# Optional code-coverage driver. Off by default so the image stays lean and
# builds cleanly on every PHP version.
ARG INSTALL_XDEBUG=0
RUN if [ "${INSTALL_XDEBUG}" = "1" ]; then install-php-extensions xdebug; fi

# Keep Composer non-interactive and quiet about Xdebug. XDEBUG_MODE is a no-op
# when Xdebug is absent and enables coverage out of the box when it is present.
# COMPOSER_HOME is pointed at a path the ./dev script mounts as a cache volume.
ENV COMPOSER_NO_INTERACTION=1 \
    COMPOSER_DISABLE_XDEBUG_WARN=1 \
    XDEBUG_MODE=coverage \
    COMPOSER_HOME=/tmp/composer

WORKDIR /app

CMD ["bash"]

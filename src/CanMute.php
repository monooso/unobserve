<?php

namespace Monooso\Unobserve;

trait CanMute
{
    /** @param string|string[] $events */
    public static function mute(string|array|null $events = null): void
    {
        $instance = resolve(static::class);
        resolve(ProxyManager::class)->register($instance, static::normalizeEvents($events));
    }

    protected static function normalizeEvents(null|string|array $events): array
    {
        if (is_null($events)) {
            $events = ['*'];
        }

        if (! is_array($events)) {
            $events = [$events];
        }

        return $events;
    }

    public static function unmute(): void
    {
        resolve(ProxyManager::class)->unregister(static::class);
    }
}

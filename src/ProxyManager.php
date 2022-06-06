<?php

namespace Monooso\Unobserve;

use Illuminate\Container\Container;

class ProxyManager
{
    public function __construct(private Container $app)
    {
    }

    public function register(object $target, array $events): void
    {
        $proxy = $this->app->make(Proxy::class, ['target' => $target, 'events' => $events]);

        $this->app->instance(get_class($target), $proxy);
    }

    public function unregister(string $targetClass): void
    {
        $this->app->forgetInstance($targetClass);
    }
}

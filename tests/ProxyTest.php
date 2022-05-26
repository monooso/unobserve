<?php

namespace Monooso\Unobserve\Tests;

use BadMethodCallException;
use Monooso\Unobserve\Proxy;
use Orchestra\Testbench\TestCase;

class ProxyTest extends TestCase
{
    /** @test */
    public function it_swallows_cloaked_events(): void
    {
        $target = new ProxyTarget;

        $actual = (new Proxy($target, ['cloaked']))->cloaked();

        $this->assertNull($actual);
    }

    /** @test */
    public function it_passes_uncloaked_events_to_the_observer(): void
    {
        $target = new ProxyTarget;

        $actual = (new Proxy($target, ['cloaked']))->uncloaked();

        $this->assertSame('uncloaked', $actual);
    }

    /** @test */
    public function it_swallows_all_events(): void
    {
        $target = new ProxyTarget;
        $proxy = new Proxy($target, ['*']);

        $this->assertNull($proxy->cloaked());
        $this->assertNull($proxy->uncloaked());
    }

    /** @test */
    public function it_raises_an_exception_for_unknown_methods(): void
    {
        $target = new ProxyTarget;

        $this->expectException(BadMethodCallException::class);

        (new Proxy($target, ['cloaked']))->unknown();
    }
}

class ProxyTarget
{
    public function uncloaked(): string
    {
        return 'uncloaked';
    }

    public function cloaked(): string
    {
        return 'cloaked';
    }
}

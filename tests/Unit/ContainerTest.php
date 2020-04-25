<?php


namespace Webasics\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Webasics\Framework\DependencyInjection\Container;
use Webasics\Framework\Exceptions\NotFoundException;
use Webasics\Framework\Route\Dispatcher;
use Webasics\Framework\Route\Router;

/**
 * Class ContainerTest
 * @package Webasics\Tests\Unit
 */
class ContainerTest extends TestCase
{

    /**
     * @test
     * @throws NotFoundException
     */
    public function itShouldContainTheRequestedDependency()
    {
        $routerMock = $this->prophesize(Router::class)->reveal();

        $container = new Container();
        $container->set(Router::class, $routerMock);

        self::assertTrue($container->has(Router::class));
        self::assertSame($routerMock, $container->get(Router::class));
    }

    /**
     * @test
     * @throws NotFoundException
     */
    public function itShouldThrowAnExceptionForMissingDependency()
    {
        self::expectException(NotFoundException::class);

        $container = new Container();
        $container->get(Dispatcher::class);
    }

}
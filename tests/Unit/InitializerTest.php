<?php

namespace Webasics\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Webasics\Framework\DependencyInjection\Container;
use Webasics\Framework\Route\Initializer;
use Webasics\Framework\Route\Router;
use Webasics\Tests\Fixtures\Controller\TestAwareController;

/**
 * Class InitializerTest
 * @package Webasics\Tests\Unit
 */
class InitializerTest extends TestCase
{

    /**
     * @test
     */
    public function itShouldReturnInheritedInterfaces()
    {
        /** @var Container|ObjectProphecy $container */
        $container = $this->prophesize(Container::class);
        $container->get(Argument::is(Router::class))->willReturn($this->prophesize(Router::class)->reveal());

        $initializer = new Initializer($container->reveal());

        $result = $initializer->loadClass(TestAwareController::class);

        self::assertInstanceOf(TestAwareController::class, $result);
    }

}
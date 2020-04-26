<?php

namespace Webasics\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Webasics\Framework\DependencyInjection\Container;
use Webasics\Framework\Route\RouteItem;
use Webasics\Framework\Exceptions\InitializerException;
use Webasics\Framework\Exceptions\InvalidResponseException;
use Webasics\Framework\Exceptions\NotFoundException;
use Webasics\Framework\Helper\ArrayHelper;
use Webasics\Framework\Route\Dispatcher;
use Webasics\Framework\Route\Initializer;

/**
 * Class DispatcherTest
 * @package Webasics\Tests\Unit
 */
class DispatcherTest extends TestCase
{

    /** @var Dispatcher */
    private Dispatcher $dispatcher;

    public function setUp(): void
    {
        $this->dispatcher = new Dispatcher(new Initializer($this->prophesize(Container::class)->reveal()));
    }

    /**
     * @param string $name
     * @param array  $data
     * @return RouteItem
     */
    private function createRouteEntity(string $name, array $data)
    {
        return new RouteItem($name, $data['path'], $data['class'], $data['action'], $data['parameters'] ?? []);
    }

    /**
     * @test
     *
     * @throws InvalidResponseException
     * @throws NotFoundException
     * @throws InitializerException
     */
    public function itShouldReturnValidResponse()
    {
        $result = $this->dispatcher->forward($this->createRouteEntity('test', RouterTest::ROUTE_COLLECTION['test']));

        self::assertInstanceOf(ResponseInterface::class, $result);
    }

    /**
     * @test
     *
     * @throws InvalidResponseException
     * @throws NotFoundException
     */
    public function itShouldThrowExceptionForInvalidResponse()
    {
        self::expectException(InvalidResponseException::class);

        $this->dispatcher->forward($this->createRouteEntity('invalid_response', RouterTest::ROUTE_COLLECTION['invalid_response']));
    }

    /**
     * @test
     *
     * @throws InvalidResponseException
     * @throws NotFoundException
     */
    public function itShouldThrowExceptionMissingMethod()
    {
        self::expectException(NotFoundException::class);

        $this->dispatcher->forward($this->createRouteEntity('invalid_method', RouterTest::ROUTE_COLLECTION['invalid_method']));
    }

}
<?php

namespace Webasics\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Webasics\Framework\Exceptions\NotFoundException;
use Webasics\Framework\Exceptions\RouterException;
use Webasics\Framework\Route\RouteCollection;
use Webasics\Framework\Route\RouteItem;
use Webasics\Tests\Fixtures\Controller\TestController;

class RouteCollectionTest extends TestCase
{

    /**
     * @test
     */
    public function itShouldContainRoutes()
    {
        $routeCollection = new RouteCollection([
            'test' => [
                'path'   => '/test',
                'class'  => TestController::class,
                'action' => 'index'
            ]
        ]);

        self::assertIsArray($routeCollection->toArray());
    }

    /**
     * @test
     *
     * @throws NotFoundException
     * @throws RouterException
     */
    public function itShouldAddRoute()
    {
        $routeCollection = new RouteCollection();

        $routeItem = new RouteItem(
            'test',
            '/test',
            TestController::class,
            'index'
        );

        $routeCollection->add($routeItem);

        self::assertSame($routeItem, $routeCollection->get('test'));
    }

    /**
     * @test
     *
     * @throws RouterException
     */
    public function itShouldRemoveRoute()
    {
        $routeCollection = new RouteCollection([
            'test' => [
                'path' => '/test',
                'class' => TestController::class,
                'action' => 'index'
            ],
            'test2' => [
                'path' => '/test2',
                'class' => TestController::class,
                'action' => 'index2'
            ]
        ]);

        $routeCollection->remove('test');

        self::assertSame([
            'test2' => [
                'path' => '/test2',
                'class' => TestController::class,
                'action' => 'index2'
            ]
        ], $routeCollection->toArray());
    }

    /**
     * @test
     */
    public function itShouldBehaveLikeAnArray()
    {
        $routeCollection = new RouteCollection([
            'test' => [
                'path' => '/test',
                'class' => TestController::class,
                'action' => 'index'
            ],
            'test2' => [
                'path' => '/test2',
                'class' => TestController::class,
                'action' => 'index2'
            ],
            'test3' => [
                'path' => '/test3',
                'class' => TestController::class,
                'action' => 'index3'
            ],
        ]);

        foreach ($routeCollection as $routeItem) {

            self::assertArrayHasKey('path', $routeItem);
            self::assertArrayHasKey('class', $routeItem);
            self::assertArrayHasKey('action', $routeItem);

            self::assertSame(TestController::class, $routeItem['class']);

            $routeItem['class'] = 'newClass';
            self::assertSame('newClass', $routeItem['class']);

            unset($routeItem['class']);

            self::expectErrorMessage('Typed property Webasics\Framework\Route\RouteItem::$class must not be accessed before initialization');

            echo $routeItem['class'];

        }
    }

}
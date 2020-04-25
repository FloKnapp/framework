<?php

namespace Framework;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\RequestInterface;
use Webasics\Framework\DependencyInjection\Container;
use Webasics\Framework\EventDispatcher\Observer;
use Webasics\Framework\Exceptions\InvalidResponseException;
use Webasics\Framework\Exceptions\NotFoundException;
use Webasics\Framework\Route\Dispatcher;
use Webasics\Framework\Route\Initializer;
use Webasics\Framework\Route\RouteCollection;
use Webasics\Framework\Route\Router;

/**
 * Class App
 * @package Framework
 */
class App
{

    const EVENT_EXCEPTION = 'app.exception';
    const EVENT_ROUTE_FOUND = 'app.route.found';
    const EVENT_ROUTE_DISPATCH = 'app.route.dispatch';
    const EVENT_CONTROLLER_FOUND = 'app.controller.found';
    const EVENT_CONTROLLER_RENDER = 'app.controller.render';

    const ENVIRONMENT_PROD = 'production';
    const ENVIRONMENT_STAGING = 'staging';
    const ENVIRONMENT_DEV = 'development';

    /** @var static */
    private static App $instance;

    /** @var string */
    private string $environment;

    /**
     * App constructor.
     * @param string $environment
     *
     * @codeCoverageIgnore
     */
    private function __construct($environment = self::ENVIRONMENT_PROD)
    {
        $this->environment = $environment;
    }

    /**
     * @param string $environment
     *
     * @return static
     *
     * @codeCoverageIgnore
     */
    public static function load($environment = self::ENVIRONMENT_PROD): self
    {
        if (!self::$instance) {
            self::$instance = new static($environment);
        }

        return self::$instance;
    }

    /**
     * @throws InvalidResponseException
     * @throws NotFoundException
     *
     * @codeCoverageIgnore
     */
    public function run()
    {
        $container = new Container();
        $observer  = new Observer();

        $container->set(Observer::class, $observer);

        $factory = new Psr17Factory();
        $request  = $factory->createRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

        $container->set(RequestInterface::class, $request);

        $initializer     = new Initializer($container);
        $dispatcher      = new Dispatcher($initializer);
        $routeCollection = new RouteCollection();
        $router          = new Router($dispatcher, $routeCollection);

        $container->set(Router::class, $router);

        static::registerEnvironmentVariables();
        static::registerEvents();

        return (string)$router->dispatch($request)->getBody();
    }

    private static function registerEnvironmentVariables()
    {

    }

    private static function registerEvents()
    {

    }

}
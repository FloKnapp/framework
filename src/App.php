<?php

namespace Webasics\Framework;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Webasics\Framework\Configuration\Config;
use Webasics\Framework\DependencyInjection\Container;
use Webasics\Framework\Event\Event\RequestCreateEvent;
use Webasics\Framework\Event\Observer;
use Webasics\Framework\Exceptions\InvalidResponseException;
use Webasics\Framework\Exceptions\NotFoundException;
use Webasics\Framework\Route\Dispatcher;
use Webasics\Framework\Route\Initializer;
use Webasics\Framework\Route\Router;

/**
 * Class App
 * @package Framework
 */
class App
{

    const EVENT_EXCEPTION         = 'app.event.exception';
    const EVENT_REQUEST_CREATE    = 'app.event.request.create';
    const EVENT_ROUTE_FOUND       = 'app.event.route.found';
    const EVENT_ROUTE_DISPATCH    = 'app.event.route.dispatch';
    const EVENT_CONTROLLER_FOUND  = 'app.event.controller.found';
    const EVENT_CONTROLLER_RENDER = 'app.event.controller.render';

    const ENVIRONMENT_PROD    = 'production';
    const ENVIRONMENT_STAGING = 'staging';
    const ENVIRONMENT_TEST    = 'test';
    const ENVIRONMENT_DEV     = 'development';

    const ERR_METHOD_NOT_FOUND_IN_CLASS = 'Method %s not found in %s.';
    const ERR_CONTROLLER_INVALID_RESPONSE = 'Controller doesn\'t return a valid response.';

    /** @var Config */
    private Config $config;

    /** @var string */
    private string $environment;

    /**
     * App constructor.
     * @param Config $config
     * @param string $environment
     */
    public function __construct(Config $config, string $environment)
    {
        $this->config      = $config;
        $this->environment = $environment;
    }

    /**
     * @throws InvalidResponseException
     * @throws NotFoundException
     */
    public function run()
    {
        $container   = new Container();
        $initializer = new Initializer($container);

        $container->set(Config::class, $this->config);

        $observer  = $initializer->loadClass(Observer::class, $this->config->get('eventListener') ?? []);
        $container->set(Observer::class, $observer);

        $request = $this->createRequestFromHeaders();
        $container->set(RequestInterface::class, $request, Request::class);

        $observer->notify(App::EVENT_REQUEST_CREATE, $request);


        $dispatcher  = new Dispatcher($initializer);
        $router      = new Router($dispatcher, $this->config->get('routes'));

        $container->set(Router::class, $router);

        static::registerEnvironmentVariables();

        $observer->notify(App::EVENT_ROUTE_DISPATCH, $router);

        return (string)$router->dispatch($request)->getBody();
    }

    /**
     * @return RequestInterface
     */
    private function createRequestFromHeaders()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestPath   = $_SERVER['REQUEST_URI'];

        $factory = new Psr17Factory();
        return $factory->createRequest($requestMethod, $requestPath);
    }

    private static function registerEnvironmentVariables()
    {

    }

}
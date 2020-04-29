<?php

namespace Webasics\Framework;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Webasics\Framework\Configuration\Config;
use Webasics\Framework\Database\EntityManager;
use Webasics\Framework\DependencyInjection\Container;
use Webasics\Framework\EventDispatcher\Observer;
use Webasics\Framework\Exceptions\NotFoundException;
use Webasics\Framework\Route\Dispatcher;
use Webasics\Framework\Route\Exception\InitializerException;
use Webasics\Framework\Route\Initializer;
use Webasics\Framework\Route\Router;

/**
 * Class App
 * @package Framework
 */
class App
{

    const EVENT_EXCEPTION             = 'app.event.exception';
    const EVENT_REQUEST_CREATED       = 'app.event.request.create';
    const EVENT_ROUTE_FOUND           = 'app.event.route.found';
    const EVENT_ROUTE_BEFORE_DISPATCH = 'app.event.route.dispatch';
    const EVENT_ROUTE_AFTER_DISPATCH  = 'app.event.route.dispatch';
    const EVENT_ROUTE_DISPATCH        = 'app.event.route.dispatch';
    const EVENT_CONTROLLER_FOUND      = 'app.event.controller.found';
    const EVENT_CONTROLLER_RENDER     = 'app.event.controller.render';

    const ENVIRONMENT_PROD    = 'production';
    const ENVIRONMENT_STAGING = 'staging';
    const ENVIRONMENT_TEST    = 'test';
    const ENVIRONMENT_DEV     = 'development';

    const ERR_METHOD_NOT_FOUND_IN_CLASS   = 'Method %s not found in %s.';
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
     * @return string
     *
     * @throws NotFoundException
     * @throws InitializerException
     */
    public function run()
    {
        $container   = new Container();
        $initializer = new Initializer($container);

        $database = new EntityManager();
        $container->set(EntityManager::class, $database, 'Database');

        $container->set(Config::class, $this->config);

        /** @var Observer $observer */
        $observer = $initializer->loadClass(Observer::class, $this->config->get('eventListener') ?? []);
        $container->set(Observer::class, $observer);

        /** @var Dispatcher $dispatcher */
        $dispatcher = $initializer->loadClass(Dispatcher::class, $initializer);
        $container->set(Dispatcher::class, $dispatcher);

        /** @var Router $router */
        $router = $initializer->loadClass(Router::class, $dispatcher, $this->config->get('routes'));
        $container->set(Router::class, $router);

        /** @var RequestInterface $request */
        $request  = $this->createRequestFromHeaders();
        $container->set(RequestInterface::class, $request, Request::class);
        $observer->notify(App::EVENT_REQUEST_CREATED, $request);

        $this->registerEnvironmentVariables();

        $result = '';

        try {

            $observer->notify(App::EVENT_ROUTE_BEFORE_DISPATCH, $router);
            $result = (string)$router->dispatch($request)->getBody();
            $observer->notify(App::EVENT_ROUTE_AFTER_DISPATCH, $router);

        } catch (\Throwable $e) {
            $observer->notify(App::EVENT_EXCEPTION, $e);
        }

        return $result;
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

    /**
     * @return bool
     */
    private function registerEnvironmentVariables()
    {
        $envFile = __DIR__ . '/../.env';

        if (!file_exists($envFile)) {
            return false;
        }

        $envs      = [];
        $envFile   = file_get_contents($envFile);
        $variables = explode(PHP_EOL, $envFile);

        foreach ($variables as $variable) {

            list($name, $value) = explode('=', trim($variable));
            $envs[trim($name)]  = trim($value);

            if (!getenv($name)) {
                putenv(trim($variable));
                define($name, $value);
                $_SERVER[$name] = $value;
            }

        }

        return true;
    }

}
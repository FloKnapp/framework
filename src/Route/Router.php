<?php

namespace Webasics\Framework\Route;

use http\Env\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Webasics\Framework\Exceptions\InvalidResponseException;
use Webasics\Framework\Exceptions\NotFoundException;

/**
 * Class Router
 * @package Framework\Route
 */
class Router
{

    /** @var Dispatcher */
    private Dispatcher $dispatcher;

    /** @var RouteCollection */
    private RouteCollection $routes;

    /**
     * Router constructor.
     * @param Dispatcher      $dispatcher
     * @param RouteCollection $routes
     */
    public function __construct(Dispatcher $dispatcher, RouteCollection $routes)
    {
        $this->dispatcher = $dispatcher;
        $this->routes     = $routes;
    }

    /**
     * @param string $name
     * @return RouteItem
     *
     * @throws NotFoundException
     */
    public function getRoute(string $name): RouteItem
    {
        return $this->routes->get($name);
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface|null
     *
     * @throws NotFoundException
     * @throws InvalidResponseException
     */
    public function dispatch(RequestInterface $request):? ResponseInterface
    {
        $routeItem = $this->matchPath($request);
        return $this->dispatcher->dispatch($routeItem);
    }

    /**
     * @param RequestInterface $request
     * @return RouteItem
     *
     * @throws NotFoundException
     */
    private function matchPath(RequestInterface $request): RouteItem
    {
        foreach ($this->routes as $route) {

            $matches   = [];
            $routePath = $route->getPath();

            // Remove / and : and insert possible route parameters to match the route path pattern
            $result  = preg_replace_callback('/\/:\w+/u', function($item) {
                $str = substr($item[0], 2);
                return '/(?<' . $str . '>[a-zäöüA-ZÄÖÜ0-9~_\-\.\:\@]+)';
            }, $routePath);

            // Replace possible found path slashes and convert into a regex
            $result = '/^' . str_replace('/', '\/', $result) . '$/u';

            // Match regex against path
            preg_match($result, urldecode($request->getUri()->getPath()), $matches);

            if ($matches) {

                $params = [];

                // Extract parameters
                array_walk($matches, function($item, $key) use (&$params) {
                    if (!is_int($key)) {
                        $params[$key] = $item;
                    }
                });

                $route->setParameters($params);

                return $route;

            }

        }

        throw new NotFoundException('Route with path "' . $request->getUri()->getPath() . '" couldn\'t be matched.');
    }

}
<?php

namespace Webasics\Framework\Route;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Webasics\Framework\Event\ObserverAwareInterface;
use Webasics\Framework\Event\ObserverAwareTrait;
use Webasics\Framework\Exceptions\InitializerException;
use Webasics\Framework\Exceptions\InvalidResponseException;
use Webasics\Framework\Exceptions\NotFoundException;
use Webasics\Framework\Helper\ArrayHelper;

/**
 * Class Router
 * @package Framework\Route
 */
class Router implements ObserverAwareInterface
{

    use ObserverAwareTrait;

    /** @var Dispatcher */
    private Dispatcher $dispatcher;

    /** @var RouteItem[] */
    private array $routes;

    /**
     * Router constructor.
     * @param Dispatcher $dispatcher
     * @param array      $routes
     */
    public function __construct(Dispatcher $dispatcher, array $routes)
    {
        $this->dispatcher = $dispatcher;
        $this->routes     = $this->transformArrayRoutes($routes);
    }

    /**
     * @param string $name
     * @return RouteItem
     *
     * @throws NotFoundException
     */
    public function getRoute(string $name): RouteItem
    {
        if (empty($this->routes[$name])) {
            throw new NotFoundException('Route with name "' . $name . '" not found.');
        }

        return $this->routes[$name];
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface|null
     *
     * @throws NotFoundException
     * @throws InvalidResponseException
     * @throws InitializerException
     */
    public function dispatch(RequestInterface $request):? ResponseInterface
    {
        $route = $this->matchPath($request);
        return $this->dispatcher->forward($route);
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

        throw new NotFoundException('No matching route for path "' . $request->getUri()->getPath() . '" found.');
    }

    private function transformArrayRoutes(array $routes)
    {
        $result = [];

        foreach ($routes as $name => $route) {

            $arr = $route;
            $arr['name'] = $name;

            $result[$name] = ArrayHelper::arrayToObject($arr, RouteItem::class);
        }

        return $result;
    }

}
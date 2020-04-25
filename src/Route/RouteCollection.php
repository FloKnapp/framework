<?php

namespace Webasics\Framework\Route;

use Webasics\Framework\Exceptions\NotFoundException;
use Webasics\Framework\Exceptions\RouterException;

class RouteCollection implements \JsonSerializable, \ArrayAccess, \Iterator
{

    /** @var RouteItem[] */
    private array $routes = [];

    /**
     * RouteCollection constructor.
     *
     * @param array $routes
     */
    public function __construct(array $routes = [])
    {
        foreach ($routes as $name => $route) {
            $this->routes[$name] = new RouteItem(
                $name, $route['path'], $route['class'], $route['action'], $route['parameters'] ?? []
            );
        }
    }

    /**
     * @param RouteItem $routeItem
     * @param bool      $overwrite
     * @return RouteItem
     */
    public function add(RouteItem $routeItem, $overwrite = false)
    {
        array_push($this->routes, $routeItem);

        return $routeItem;
    }

    /**
     * @param $name
     *
     * @return bool
     * @throws RouterException
     */
    public function remove($name)
    {
        if (false === $this->exists($name)) {
            throw new RouterException('Deleting route failed: Route "' . $name . '" couldn\'t be found.');
        }

        $i = 0;

        foreach ($this->routes as $routeItem) {

            if ($routeItem->getName() === $name) {
                array_splice($this->routes, $i, 1);
                break;
            }

            ++$i;

        }

        return true;
    }

    /**
     * @param string $name
     * @return RouteItem
     *
     * @throws NotFoundException
     */
    public function get(string $name): RouteItem
    {
        $result = null;

        if (false === $this->exists($name)) {
            throw new NotFoundException('No route item for name "' . $name . '" found.');
        }

        foreach ($this->routes as $routeItem) {
            if ($routeItem->getName() === $name) {
                $result = $routeItem;
                break;
            }
        }

        return $result;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function exists(string $name): bool
    {
        foreach ($this->routes as $routeItem) {

            if ($routeItem->getName() === $name) {
                return true;
            }

        }

        return false;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [];

        foreach ($this->routes as $route) {

            $result[$route->getName()] = [
                'path' => $route->getPath(),
                'class' => $route->getClass(),
                'action' => $route->getAction()
            ];

        }

        return $result;
    }

    public function jsonSerialize()
    {
        return json_encode($this->toArray());
    }

    public function offsetExists($offset)
    {
        return null !== $this->routes[$offset] ?? null;
    }

    public function offsetGet($offset)
    {
        return $this->routes[$offset] ?? null;
    }

    public function offsetSet($offset, $value)
    {
        $this->routes[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->routes[$offset]);
    }

    public function current()
    {
        return current($this->routes);
    }

    public function next()
    {
        return next($this->routes);
    }

    public function key()
    {
        return key($this->routes);
    }

    public function valid()
    {
        return !empty($this->current());
    }

    public function rewind()
    {
        reset($this->routes);
    }

}
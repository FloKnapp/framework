<?php

namespace Webasics\Framework\Route;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Webasics\Framework\Exceptions\InvalidResponseException;
use Webasics\Framework\Exceptions\MethodNotFoundException;
use Webasics\Framework\Exceptions\NotFoundException;

/**
 * Class Dispatcher
 * @package Webasics\Framework\Route
 */
class Dispatcher
{

    /**
     * @param RouteItem $routeItem
     * @return ResponseInterface
     *
     * @throws InvalidResponseException
     * @throws NotFoundException
     */
    public function dispatch(RouteItem $routeItem): ResponseInterface
    {
        $classStr = $routeItem->getClass();
        $action   = $routeItem->getAction();
        $classObj = new $classStr();

        if (!method_exists($classObj, $action)) {
            throw new NotFoundException();
        }

        $result = call_user_func_array([$classObj, $action], $routeItem->getParameters());

        if (!$result instanceof ResponseInterface) {
            throw new InvalidResponseException('Controller action doesn\'t return a valid response.');
        }

        return $result;
    }

}
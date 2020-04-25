<?php

namespace Webasics\Framework\Route;

use Psr\Http\Message\ResponseInterface;
use Webasics\Framework\Exceptions\InvalidResponseException;
use Webasics\Framework\Exceptions\NotFoundException;

/**
 * Class Dispatcher
 * @package Webasics\Framework\Route
 */
class Dispatcher
{

    private Initializer $initializer;

    /**
     * Dispatcher constructor.
     * @param Initializer $initializer
     */
    public function __construct(Initializer $initializer)
    {
        $this->initializer = $initializer;
    }

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
            throw new NotFoundException('Method not found in "' . $classStr. '".');
        }

        $classObj = $this->initializer->loadClass($classStr);

        $result = call_user_func_array([$classObj, $action], $routeItem->getParameters());

        if (!$result instanceof ResponseInterface) {
            throw new InvalidResponseException('Controller action doesn\'t return a valid response.');
        }

        return $result;
    }

}
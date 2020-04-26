<?php

namespace Webasics\Framework\Route;

use Webasics\Framework\App;
use Psr\Http\Message\ResponseInterface;
use Webasics\Framework\Route\RouteItem;
use Webasics\Framework\Exceptions\InitializerException;
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
     * @throws InitializerException
     */
    public function forward(RouteItem $routeItem): ResponseInterface
    {
        $classStr = $routeItem->getClass();
        $action   = $routeItem->getAction();
        $classObj = new $classStr();

        if (!method_exists($classObj, $action)) {
            throw new NotFoundException(sprintf(App::ERR_METHOD_NOT_FOUND_IN_CLASS, $action, $classStr));
        }

        $classObj = $this->initializer->loadClass($classStr);

        $result = call_user_func_array([$classObj, $action], $routeItem->getParameters());

        if (!$result instanceof ResponseInterface) {
            throw new InvalidResponseException(App::ERR_CONTROLLER_INVALID_RESPONSE);
        }

        return $result;
    }

}
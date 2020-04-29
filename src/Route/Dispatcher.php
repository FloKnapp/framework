<?php

namespace Webasics\Framework\Route;

use Webasics\Framework\App;
use Psr\Http\Message\ResponseInterface;
use Webasics\Framework\EventDispatcher\ObserverAwareInterface;
use Webasics\Framework\EventDispatcher\ObserverAwareTrait;
use Webasics\Framework\Route\Exception\InitializerException;
use Webasics\Framework\Route\Exception\InvalidResponseException;
use Webasics\Framework\Exceptions\NotFoundException;

/**
 * Class Dispatcher
 * @package Webasics\Framework\Route
 */
class Dispatcher implements ObserverAwareInterface
{

    use ObserverAwareTrait;

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

        $classObj = $this->initializer->loadClass($classStr);

        if (null === $classObj) {
            throw new InitializerException('Object creation failed.');
        }

        if (!method_exists($classObj, $action)) {
            throw new NotFoundException(sprintf(App::ERR_METHOD_NOT_FOUND_IN_CLASS, $action, $classStr));
        }

        $this->getObserver()->notify(App::EVENT_CONTROLLER_FOUND, $classObj);

        $result = call_user_func_array([$classObj, $action], $routeItem->getParameters());

        $this->getObserver()->notify(App::EVENT_CONTROLLER_RENDER, $result);

        if (!$result instanceof ResponseInterface) {
            throw new InvalidResponseException(App::ERR_CONTROLLER_INVALID_RESPONSE);
        }

        return $result;
    }

}
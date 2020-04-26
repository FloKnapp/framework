<?php

namespace Webasics\Framework\Route;

use Webasics\Framework\DependencyInjection\Container;
use Webasics\Framework\Exceptions\InitializerException;
use Webasics\Framework\Exceptions\NotFoundException;

/**
 * Class Initializer
 * @package Webasics\Framework\Route
 */
class Initializer
{

    /** @var Container */
    private Container $container;

    /**
     * Initializer constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $class
     * @return object
     *
     * @throws InitializerException
     * @throws NotFoundException
     */
    public function loadClass(string $class)
    {
        if (false === class_exists($class)) {
            throw new InitializerException('Class "' . $class . '" doesn\'t exist and couldn\'t be loaded.');
        }

        $dependencies = $this->getClassDependencies($class);
        $classObj     = new $class();

        foreach ($dependencies as $setter => $dependency) {

            if (class_exists($dependency)) {

                if ($dependency === Container::class) {
                    $classObj->$setter($this->container);
                    continue;
                }

                $classObj->$setter($this->container->get($dependency));

            }

        }

        return $classObj;
    }

    /**
     * @param string $class
     * @return array
     *
     * @throws InitializerException
     */
    private function getClassDependencies(string $class): array
    {
        $dependencies = [];
        $implementedInterfaces = class_implements($class);

        foreach ($implementedInterfaces as $interface) {
            $dependencies = $this->getInterfaceClassDependencies($interface);
        }

        return $dependencies;
    }

    /**
     * @param $interface
     * @return array
     *
     * @throws InitializerException
     */
    private function getInterfaceClassDependencies(string $interface): array
    {
        try {

            $dependencies = [];
            $reflection   = new \ReflectionClass($interface);

            foreach ($reflection->getMethods() as $method) {

                if (0 !== strpos($method->getName(), 'set')) {
                    continue;
                }

                foreach ($method->getParameters() as $parameter) {

                    if ($parameter->getClass()) {
                        $dependencies[$method->getName()] = $parameter->getClass()->getName();
                    }

                }

            }

            return $dependencies;

        } catch (\ReflectionException $e) {
            throw new InitializerException('Error while detecting class dependencies: ' . $e->getMessage());
        }

    }

}
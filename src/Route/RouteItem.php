<?php

namespace Webasics\Framework\Route;

/**
 * Class RouteConfig
 * @package Webasics\Framework\Route
 */
class RouteItem implements \ArrayAccess
{

    /** @var string */
    private string $name;

    /** @var string */
    private string $path;

    /** @var string */
    private string $class;

    /** @var string */
    private string $action;

    /** @var array */
    private array $parameters;

    /**
     * RouteConfig constructor.
     *
     * @param string $name
     * @param string $path
     * @param string $class
     * @param string $action
     * @param array  $parameters
     */
    public function __construct(string $name, string $path, string $class, string $action, array $parameters = [])
    {
        $this->name       = $name;
        $this->path       = $path;
        $this->class      = $class;
        $this->action     = $action;
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $item[$this->getName()] = [
            'path'       => $this->getPath(),
            'class'      => $this->getClass(),
            'action'     => $this->getAction(),
            'parameters' => $this->getParameters()
        ];

        return $item;
    }

    public function offsetExists($offset)
    {
        return !empty($this->$offset);
    }

    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }

}
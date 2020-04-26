<?php

namespace Webasics\Framework\DependencyInjection;

use Psr\Container\ContainerInterface;
use Webasics\Framework\Exceptions\NotFoundException;

/**
 * Class Container
 * @package Webasics\Framework\DependencyInjection
 */
class Container implements ContainerInterface
{

    /** @var array */
    private array $dependencies;

    /**
     * @param string $id
     * @return object
     *
     * @throws NotFoundException
     */
    public function get($id): object
    {
        $object = $this->dependencies[$id] ?? null;

        if (null === $object) {
            throw new NotFoundException('No dependency with id "' . $id . '" found.');
        }

        return $object;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id): bool
    {
        return !empty($this->dependencies[$id]);
    }

    /**
     * @param string $id
     * @param object $object
     * @param array  $aliases
     */
    public function set(string $id, object $object, ...$aliases)
    {
        $this->dependencies[$id] = $object;

        foreach ($aliases as $alias) {
            $this->dependencies[$alias] = $this->dependencies[$id];
        }
    }

}
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
    private array $storage;

    /**
     * @param string $id
     * @return mixed
     *
     * @throws NotFoundException
     */
    public function get($id)
    {
        $object = $this->storage[$id] ?? null;

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
        return !empty($this->storage[$id]);
    }

    /**
     * @param string $id
     * @param object $object
     * @param array  $aliases
     */
    public function set(string $id, object $object, ...$aliases)
    {
        $this->storage[$id] = $object;

        foreach ($aliases as $alias) {
            $this->storage[$alias] = $this->storage[$id];
        }
    }

}
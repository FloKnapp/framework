<?php

namespace Webasics\Framework\Session;

use Webasics\Framework\Session\Adapter\GenericAdapter;
use Webasics\Framework\Session\Adapter\SessionAdapterInterface;

/**
 * Class Session
 * @package Webasics\Framework\Session
 */
class Session
{

    /** @var SessionAdapterInterface */
    private SessionAdapterInterface $adapter;

    /**
     * Session constructor.
     * @param SessionAdapterInterface|null $adapter
     */
    public function __construct(SessionAdapterInterface $adapter = null)
    {
        if (null === $adapter) {
            $this->adapter = new GenericAdapter();
        }
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function get(string $name)
    {
        return $this->adapter->get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function set(string $name, $value)
    {
        $this->adapter->set($name, $value);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function delete(string $name)
    {
        return $this->adapter->delete($name);
    }

    /**
     * @param string $name
     * @param $value
     */
    public function setFlashMessage(string $name, $value)
    {
        $this->adapter->setFlashMessage($name, $value);
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getFlashMessage(string $name)
    {
        return $this->adapter->getFlashMessage($name);
    }

}
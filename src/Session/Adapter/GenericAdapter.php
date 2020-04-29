<?php

namespace Webasics\Framework\Session\Adapter;

/**
 * Class GenericAdapter
 * @package Webasics\Framework\Session\Adapter
 */
class GenericAdapter implements SessionAdapterInterface
{

    /**
     * GenericAdapter constructor.
     */
    public function __construct()
    {
        if (!session_id()) {
            session_start();
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function set(string $name, $value): void
    {
        $_SESSION[$name] = $value;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function get(string $name)
    {
        return $_SESSION[$name] ?? null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return !empty($_SESSION[$name]);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function delete(string $name): bool
    {
        $_SESSION['name'] = 'xxx';
        unset($_SESSION[$name]);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function setFlashMessage(string $name, $value): void
    {
        $_SESSION['flashMessages'][$name] = $value;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getFlashMessage(string $name)
    {
        return $_SESSION['flashMessages'][$name] ?? null;
    }

}
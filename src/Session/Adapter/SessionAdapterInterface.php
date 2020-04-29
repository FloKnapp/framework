<?php

namespace Webasics\Framework\Session\Adapter;

/**
 * Interface SessionAdapterInterface
 * @package Webasics\Framework\Session\Adapter
 */
interface SessionAdapterInterface
{

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return void
     */
    public function set(string $name, $value): void;

    /**
     * @param string $name
     * @return mixed|null
     */
    public function get(string $name);

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * @param string $name
     * @return bool
     */
    public function delete(string $name): bool;

    /**
     * @param string $name
     * @param mixed  $value
     * @return void
     */
    public function setFlashMessage(string $name, $value): void;

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getFlashMessage(string $name);

}
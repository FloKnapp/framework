<?php

namespace Webasics\Framework\Route;

/**
 * Class RouterAwareTrait
 * @package Webasics\Framework\Route
 */
trait RouterAwareTrait
{

    /** @var Router */
    protected Router $router;

    /**
     * @param Router $router
     * @return void
     */
    public function setRouter(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

}
<?php

namespace Webasics\Framework\Route;

/**
 * Interface RouterAwareInterface
 * @package Webasics\Framework\Route
 */
interface RouterAwareInterface
{

    /**
     * @param Router $router
     * @return void
     */
    public function setRouter(Router $router);

}
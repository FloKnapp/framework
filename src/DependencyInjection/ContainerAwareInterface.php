<?php

namespace Webasics\Framework\DependencyInjection;

/**
 * Interface ContainerAwareInterface
 * @package Webasics\Framework\DependencyInjection
 */
interface ContainerAwareInterface
{

    /**
     * @param Container $container
     * @return void
     */
    public function setContainer(Container $container);

}
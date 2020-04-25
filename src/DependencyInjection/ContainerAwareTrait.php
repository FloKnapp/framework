<?php

namespace Webasics\Framework\DependencyInjection;

/**
 * Trait ContainerAwareTrait
 * @package Webasics\Framework\DependencyInjection
 */
trait ContainerAwareTrait
{

    /** @var Container */
    protected Container $container;

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

}
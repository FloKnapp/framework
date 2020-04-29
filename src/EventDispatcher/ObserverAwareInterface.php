<?php

namespace Webasics\Framework\EventDispatcher;

/**
 * Interface ObserverAwareInterface
 * @package Webasics\Framework\Event
 */
interface ObserverAwareInterface
{

    /**
     * @param Observer $observer
     * @return void
     */
    public function setObserver(Observer $observer);

    /**
     * @return Observer
     */
    public function getObserver(): Observer;

}
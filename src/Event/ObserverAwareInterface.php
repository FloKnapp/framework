<?php

namespace Webasics\Framework\Event;

/**
 * Interface ObserverAwareInterface
 * @package Webasics\Framework\Event
 */
interface ObserverAwareInterface
{

    /**
     * @param Observer $observer
     * @return mixed
     */
    public function setObserver(Observer $observer);

}
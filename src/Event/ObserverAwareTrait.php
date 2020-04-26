<?php

namespace Webasics\Framework\Event;

/**
 * Trait ObserverAwareTrait
 * @package Webasics\Framework\Event
 */
trait ObserverAwareTrait
{

    /** @var Observer */
    protected Observer $observer;

    /**
     * @param Observer $observer
     */
    public function setObserver(Observer $observer)
    {
        $this->observer = $$observer;
    }

}
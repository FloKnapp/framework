<?php

namespace Webasics\Framework\EventDispatcher;

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
        $this->observer = $observer;
    }

    /**
     * @return Observer
     */
    public function getObserver(): Observer
    {
        return $this->observer;
    }

}
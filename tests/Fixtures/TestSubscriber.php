<?php

namespace Framework\Tests\Fixtures;

use Framework\Event\EventSubscriberInterface;

/**
 * Class TestSubscriber
 * @package Framework\Tests\Fixtures
 */
class TestSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            ''
        ];
    }

}
<?php

namespace Framework\Tests\Fixtures;

use Framework\EventDispatcher\EventSubscriberInterface;

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